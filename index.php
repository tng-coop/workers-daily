<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker's Daily Report</title>
    <script>
        // Cookie handling functions
        function getCookie(name) {
            let cookieArray = document.cookie.split('; ');
            let cookie = cookieArray.find(row => row.startsWith(name + '='));
            return cookie ? decodeURIComponent(cookie.split('=')[1]) : null;
        }

        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
        }

        function deleteCookie(name) {
            document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        // Update the subject line with the current date and optionally the user's name
        function updateSubjectLine() {
            const userName = getCookie('userName');
            const userDate = new Date();
            const formattedDate = userDate.toISOString().split('T')[0]; // Formats the date as "YYYY-MM-DD"
            let subjectLine = `${formattedDate}`; // Start with just the date
            if (userName) {
                subjectLine += `: ${userName} : `; // Append the user's name if available
                document.getElementById('name').value = userName; // Prefill the name input
            }
            document.getElementById('suggestedSubject').innerText = 'Suggested Subject Line: ' + subjectLine;
        }

        // Handle the form submission to set the user's name
        function handleFormSubmit(event) {
            event.preventDefault(); // Prevent the default form submission
            const userName = document.getElementById('name').value;
            setCookie('userName', userName, 365); // Store name for 365 days
            updateSubjectLine(); // Update with the new name
        }

        // Clear the stored data and refresh the page
        function clearData() {
            deleteCookie('userName');
            window.location.reload(); // Refresh to show default state
        }

        // Setup event listeners and initialize on page load
        window.onload = function() {
            updateSubjectLine();
            document.getElementById('nameForm').addEventListener('submit', handleFormSubmit);
        };
    </script>
</head>
<body>
    <h1>Worker's Daily Report</h1>
    <div id="suggestedSubject">Loading suggested subject line...</div>
    <form id="nameForm">
        <input type="text" id="name" name="name" placeholder="Enter your name">
        <button type="submit">Set Name</button>
    </form>
    <button onclick="clearData()">Clear Data</button>
</body>
</html>
