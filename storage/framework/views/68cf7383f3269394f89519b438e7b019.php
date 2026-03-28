<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Engine UUD 1945</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #0f172a;
    color: #e2e8f0;
    font-family: 'Segoe UI', sans-serif;
}
.search-box {
    max-width: 700px;
    margin: 80px auto 20px;
}
.card-result {
    background: #1e293b;
    border: none;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
}
mark {
    background: #facc15;
    color: black;
}
</style>
</head>
<body>

<div class="container text-center">
    <h1 class="mt-5">🔍 Search UUD 1945</h1>
    <p class="text-secondary">Cari pasal dengan cepat & akurat</p>

    <div class="search-box">
        <input type="text" id="search" class="form-control form-control-lg" placeholder="Ketik kata kunci...">

        <div class="d-flex mt-3 gap-2">
            <select id="filter_bab" class="form-select">
                <option value="all">Semua BAB</option>
                <option value="BAB I">BAB I</option>
                <option value="BAB II">BAB II</option>
                <option value="BAB III">BAB III</option>
                <option value="BAB IV">BAB IV</option>
                <option value="BAB V">BAB V</option>
                <option value="BAB VI">BAB VI</option>
                <option value="BAB VII">BAB VII</option>
                <option value="BAB VIII">BAB VIII</option>
                <option value="BAB IX">BAB IX</option>
                <option value="BAB X">BAB X</option>
            </select>

            <button class="btn btn-primary" onclick="cariData()">Cari</button>
        </div>
    </div>

    <div id="info" class="mb-3"></div>
    <div id="hasil" class="text-start"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let delayTimer;

$("#search").on("keyup", function(){
    clearTimeout(delayTimer);
    delayTimer = setTimeout(cariData, 500);
});

function highlight(text, keyword){
    let regex = new RegExp(`(${keyword})`, "gi");
    return text.replace(regex, "<mark>$1</mark>");
}

function cariData(){
    let cari = $("#search").val();
    let filter = $("#filter_bab").val();

    if(cari.trim() === "") return;

    $("#hasil").html("<div class='text-center'>🔍 Mencari...</div>");

    $.ajax({
        url: "search.php",
        method: "GET",
        data: {
            q: cari,
            rank: 10,
            filter: filter
        },
        success: function(data){
            if(data.length === 0){
                $("#hasil").html(`
                    <div class="alert alert-warning text-center">
                        ❌ Tidak ditemukan<br>
                        <small>Coba kata lain</small>
                    </div>
                `);
                return;
            }

            $("#info").html(`Ditemukan ${data.length} hasil`);

            let html = "";

            data.forEach(item => {
                let isi = highlight(item.isi, cari);
                let pasal = highlight(item.pasal, cari);

                html += `
                    <div class="card-result">
                        <h5>${item.bab}</h5>
                        <h6>${pasal}</h6>
                        <p>${isi.substring(0, 300)}...</p>
                        <small>Score: ${parseFloat(item.score).toFixed(4)}</small>
                    </div>
                `;
            });

            $("#hasil").html(html);

            $('html, body').animate({
                scrollTop: $("#hasil").offset().top
            }, 500);
        }
    });
}
</script>

</body>
</html><?php /**PATH C:\matkul semester 4\tki\SearchEngine\resources\views/landing.blade.php ENDPATH**/ ?>