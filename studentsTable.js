var table;  

function drawCountryTable(data) {
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
            { title: "Info", field: "id", formatter: "html", cellClick: function(e, cell) {
                window.open("studentTasks.php?id=" + cell.getValue());
            }, 
            formatter: function() {
                return "<button class='btn btn-info'>Info</button>";
            }
            },
        
        ],
    });

    table.on("rowClick", function(e, row){
        for(let i=0; i<data.length;++i){
            if(row.getData().name === data[i].name && row.getData().surname === data[i].surname){
                window.open("studentInfo.php?id="+ data[i].student_id);
                break;
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
            drawCountryTable(data);
        });
}, false);