var table;

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
            { title: "Name", field: "name" },
            { title: "Surname", field: "surname" },
            { title: "ID", field: "id" },
            { title: "Generated exercises", field: "generatedExercises" },
            { title: "Submitted exercises", field: "submittedExercises" },
            { title: "Points earned", field: "earnedPoints" },
            {
                title: "Info",
                field: "id",
                formatter: function(cell) {
                    return "<button class='btn btn-info info-button' data-id='" + cell.getValue() + "'>Info</button>";
                },
            },
        ],
    });

    document.querySelector("#students").addEventListener("click", function(e) {
        var target = e.target;

        if (target.classList.contains("info-button")) {
            var studentId = target.getAttribute("data-id");
            window.open("studentTasks.php?id=" + studentId);
        } else {
            var row = target.closest(".tabulator-row");
            if (row) {
                var rowData = table.getRow(row).getData();
                window.open("studentInfo.php?id=" + rowData.id);
            }
        }
    });

}



window.addEventListener("load", function () {
    fetch('get-students-all.php')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            drawStudentTable(data);
        });
}, false);