<!DOCTYPE html>
<html>
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <title>User Documentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #555;
            margin-bottom: 10px;
        }

        p {
            line-height: 1.5;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        .code {
            font-family: "Courier New", monospace;
            background-color: #f5f5f5;
            padding: 5px;
        }

        .button {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>User Documentation</h1>
    <h3>Teacher</h3>
    <h2>Logging In</h2>
  
    <p>
        To access the application, follow these steps:
    </p>
    <ol>
        <li>Open a web browser and go to [application link].</li>
        <li>You will see the login page.</li>
        <li>Enter your login credentials, such as username and password.</li>
        <li>Click the "Log In" button to sign in.</li>
        <li>If you entered the correct credentials, you will be redirected to the main application page.</li>
    </ol>

    <h2>Changing the Language</h2>
    <p>
        To change the language of the application, follow these steps:
    </p>
    <ol>
        <li>In the top navigation bar, you will see flags representing different countries.</li>
        <li>To change the language, click on the flag of the desired country.</li>
        <li>Select your preferred language from the available options.</li>
        <li>The application will automatically reload in the selected language.</li>
    </ol>

    <h2>Uploading a LaTeX File</h2>
    <p>
        To upload a LaTeX file, follow these steps:
    </p>
    <ol>
        <li>On the main application page, locate the "Upload LaTeX File" section.</li>
        <li>Click the "Browse" or "Choose File" button to select a LaTeX file from your computer.</li>
        <li>Make sure the selected file has the ".tex" extension.</li>
        <li>Click the "Upload" button to upload the file to the server.</li>
        <li>If the upload is successful, you will see a message indicating that the file has been uploaded.</li>
    </ol>

    <h2>Viewing the List of Students</h2>
    <p>
        To view the list of students, follow these steps:
    </p>
    <ol>
        <li>On the main application page, find the "All Students" section.</li>
        <li>In this section, a table with all the students will be displayed.</li>
        <li>You can view information about each student, such as their name, surname, and other data.</li>
        <li>As a teacher, you can click
on a student's row in the table to access additional functionality.</li>
</ol>

<h2>Additional Functionality for Teachers</h2>
<p>
    As a teacher, clicking on a student's row in the table provides the following functionality:
</p>
<ul>
    <li>Clicking on the "Info" button in the row will open the student's information page.</li>
    <li>Clicking anywhere else on the row will open the student's tasks page.</li>
</ul>

<h2>Exporting to CSV</h2>
<p>
    To export the student list to CSV, follow these steps:
</p>
<ol>
    <li>On the main application page, locate the "Export to CSV" button.</li>
    <li>Click the "Export to CSV" button to download the student table in CSV format.</li>
    <li>The CSV file will be saved on your computer.</li>
</ol>

<p>
    These are the basic features and actions you can perform in the application. If you encounter any issues or have questions, please reach out to the system administrator or the application developer for further assistance.
</p>

<p>
    Thank you for using our application!
</p>
<h3>Student</h3>
<h2>Logging In</h2>
    <p>
        To access the application, follow these steps:
    </p>
    <ol>
        <li>Open a web browser and go to [application link].</li>
        <li>You will see the login page.</li>
        <li>Enter your login credentials, such as username and password.</li>
        <li>Click the "Log In" button to sign in.</li>
        <li>If you entered the correct credentials, you will be redirected to the main application page.</li>
    </ol>

    <h2>Changing the Language</h2>
    <p>
        To change the language of the application, follow these steps:
    </p>
    <ol>
        <li>In the top navigation bar, you will see flags representing different countries.</li>
        <li>To change the language, click on the flag of the desired country.</li>
        <li>Select your preferred language from the available options.</li>
        <li>The application will automatically reload in the selected language.</li>
    </ol>

    <h2>Generating and Submitting Tasks</h2>
    <p>
        As a student, you can generate and submit tasks using the following steps:
    </p>
    <ol>
        <li>On the main application page, you will find the "Task Overview" section.</li>
        <li>To generate a task, click the "Generate Task" button.</li>
        <li>Select the files for generating the task by checking the corresponding checkboxes.</li>
        <li>After generating the task, it will be displayed below.</li>
        <li>If the task includes an image, it will be shown as well.</li>
        <li>To submit your solution for the generated task, enter your solution in the provided textarea.</li>
        <li>Click the "Send" button to submit your solution.</li>
    </ol>

    <h2>Additional Functionality</h2>
    <p>
        This application also includes the following additional functionality:
    </p>
    <ul>
        <li>You can change your language preference by selecting the language flag in the top navigation bar.</li>
        <li>You can log out of the application by clicking the "Log Out" button in the top navigation bar.</li>
    </ul>

    These are the basic features and actions you can perform in the application. If you encounter any issues or have questions, please reach out to the system administrator or the application developer for further assistance.
</p>
    <button id="generatePdfButton">Generate PDF</button>

    <script>
        const { jsPDF } = window.jspdf;
document.getElementById("generatePdfButton").addEventListener("click", function() {

    var doc = new jsPDF();

    var docContent = document.body.innerText;

    var lines = docContent.split('\n');

    lines.pop();

    docContent = lines.join('\n');

    var splitText = doc.splitTextToSize(docContent, 180); 
    var pageHeight = doc.internal.pageSize.height; 
    var lineHeight = 10; 
    var margin = 20; 
    var currentHeight = margin; 

    for (var i = 0; i < splitText.length; i++) {
        if (currentHeight + lineHeight > pageHeight - margin) { 
            doc.addPage(); 
            currentHeight = margin; 
        }
        doc.text(splitText[i], margin, currentHeight); 
        currentHeight += lineHeight; 
    }

    doc.save('my_document.pdf');
});
</script>



    </body>