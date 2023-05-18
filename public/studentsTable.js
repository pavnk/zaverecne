var table;
var translation;

function drawStudentTable(data) {
    var tableData = [];

    for (let i = 0; i < data.length; ++i) {
        var rowData = {
            name: data[i].name,
            surname: data[i].surname,
            id: data[i].student_id,
            generatedExercises: data[i].generatedTasks || 0,
            submittedExercises: data[i].submittedTasks || 0,
            earnedPoints: data[i].earnedPoints || 0,
        };

        tableData.push(rowData);
    }

    table = new Tabulator("#students", {
        data: tableData,
        layout: "fitColumns",
        columns: [
            { title: translation.name, field: "name" },
            { title: translation.surname, field: "surname" },
            { title: translation.id, field: "id" },
            { title: translation.generatedExercises, field: "generatedExercises" },
            { title: translation.submittedExercises, field: "submittedExercises" },
            { title: translation.earnedPoints, field: "earnedPoints" },
            {
                title: translation.info,
                field: "id",
                formatter: function(cell) {
                    return "<button class='btn btn-info info-button' data-id='" + cell.getValue() + "'>" + translation.info + "</button>";
                },
            },
        ],
    });

    document.querySelector("#students").addEventListener("click", function(e) {
        var target = e.target;

        if (target.classList.contains("info-button")) {
            var studentId = target.getAttribute("data-id");
            window.open("studentTasks.php?id=" + studentId);
        }
    });
}

var translationFilePath;

if (language === 'en') {
    translationFilePath = 'languages/translations_en.json';
} else if (language === 'sk') {
    translationFilePath = 'languages/translations_sk.json';
} else {
    translationFilePath = 'languages/translations_en.json';
}

fetch(translationFilePath)
    .then(function(response) {
        return response.json();
    })
    .then(function(translationData) {
        translation = translationData;

        fetch('get-students-all.php')
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                drawStudentTable(data);
            });
    })
    .catch(function(error) {
        console.error('Error loading translation file:', error);
    });

window.addEventListener("load", function () {
    fetch('get-students-all.php')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            drawStudentTable(data);
        });
}, false);
