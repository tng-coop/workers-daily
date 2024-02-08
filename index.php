<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workers' Daily Report Email Subject Helper</title>
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

        // Function to calculate week number
        function getWeekNumber(d) {
            d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
            var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
            return weekNo;
        }

        // Update the subject line, mailto link, and optionally the user's name and default content
        function updateSubjectLineAndMailto() {
            const userName = getCookie('userName');
            const defaultContent = getCookie('defaultContent');
            const userDate = new Date();
            const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = daysOfWeek[userDate.getDay()];
            const weekNumber = getWeekNumber(userDate);
            const formattedDate = `${userDate.toISOString().split('T')[0]} (${dayName}, Week ${weekNumber})`;
            let subjectLine = `${formattedDate}`;
            if (userName) {
                subjectLine += `: ${userName}: `;
                document.getElementById('name').value = userName;
            }
            const subjectDisplay = 'Suggested Subject Line: ' + subjectLine;
            document.getElementById('suggestedSubject').innerText = subjectDisplay;

            if (defaultContent) {
                document.getElementById('defaultContent').value = defaultContent;
            }

        }

    // Refactored save function
    function saveNameAndContent() {
        const userName = document.getElementById('name').value;
        const defaultContent = document.getElementById('defaultContent').value;
        setCookie('userName', userName, 365); // Store name for 365 days
        setCookie('defaultContent', defaultContent, 365); // Store default content for 365 days
        updateSubjectLineAndMailto(); // Update with the new name and content
    }

    // Handle the form submission using the new save function
    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent the default form submission
        saveNameAndContent(); // Save name and content
    }


        // Clear the stored data and refresh the page
        function clearData() {
            deleteCookie('userName');
            deleteCookie('defaultContent');
            window.location.reload(); // Refresh to show default state
        }

        function sendEmail() {
            saveNameAndContent();
    const userName = getCookie('userName');
    const defaultContent = getCookie('defaultContent') || ''; // Ensure there's a default value
    const userDate = new Date();
    const weekNumber = getWeekNumber(userDate);
    const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][userDate.getDay()];
    const formattedDate = `${userDate.toISOString().split('T')[0]} (${dayName}, Week ${weekNumber})`;
    let subjectLine = `${formattedDate}`;
    if (userName) {
        subjectLine += `: ${userName}`;
    }

    const mailtoLink = `mailto:workers-daily@lists.mayfirst.org?subject=${encodeURIComponent(subjectLine)}&body=${encodeURIComponent(defaultContent)}`;
    window.open(mailtoLink, '_blank');
}
        // Setup event listeners and initialize on page load
        window.onload = function() {
    updateSubjectLineAndMailto();
    document.getElementById('dataForm').addEventListener('submit', handleFormSubmit);
    document.getElementById('sendEmailButton').addEventListener('click', sendEmail); // This line is added
};
    </script>
</head>
<body>
    <h1>Workers' Daily</h1>
    <div id="suggestedSubject">Loading suggested subject line...</div>
    <form id="dataForm">
        <input type="text" id="name" name="name" placeholder="Enter your name"><br>
        <textarea id="defaultContent" name="defaultContent" placeholder="Enter default email content"></textarea><br>
        <button type="submit">Save Name and Content</button>
    </form>
    <button onclick="clearData()">Clear Name and Content</button>
    <button id="sendEmailButton" style="margin-top: 10px; display: inline-block;">Send Report via Email</button>
    <br>
    <br>
    <a href="https://lists.mayfirst.org/mailman/listinfo/workers-daily" target="_blank" style="margin-top: 20px; display: inline-block;">List Info</a>
</body>
</html>
