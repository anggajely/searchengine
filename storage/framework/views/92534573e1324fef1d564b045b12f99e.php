<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> UUD 1945</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0d1117; color: #c9d1d9; }
        .google-header { border-bottom: 1px solid #30363d; padding: 20px 30px; display: flex; align-items: center; background-color: #161b22; gap: 15px;}
        .logo-text { font-size: 26px; font-weight: 800; color: #58a6ff; margin-right: 15px; letter-spacing: -1px; text-shadow: 0 0 10px rgba(88,166,255,0.4); }
        .logo-text span.r { color: #ff7b72; } .logo-text span.y { color: #f2cc60; } .logo-text span.g { color: #3fb950; }
        
        .search-box { background: #0d1117; border: 1px solid #30363d; border-radius: 24px; padding: 10px 20px; flex-grow: 1; max-width: 700px; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;}
        .search-box:focus-within { box-shadow: 0 0 15px rgba(88,166,255,0.2); border-color: #58a6ff; }
        .search-input { background: transparent; border: none; outline: none; flex-grow: 1; font-size: 16px; color: #c9d1d9; }
        
        /* Styling Khusus Dropdown Filter */
        .filter-select { background: #161b22; color: #8b949e; border: 1px solid #30363d; border-radius: 15px; padding: 5px 10px; font-size: 13px; outline: none; cursor: pointer; transition: 0.2s;}
        .filter-select:hover { border-color: #8b949e; color: #c9d1d9;}
        
        .search-btn { background: none; border: none; color: #58a6ff; font-weight: bold; cursor: pointer; outline: none; transition: 0.2s; }
        .search-btn:hover { color: #79c0ff; transform: scale(1.05); }

        .results-container { padding: 30px 150px; max-width: 1000px; }
        .result-item { margin-bottom: 35px; padding: 20px; background: #161b22; border-radius: 10px; border: 1px solid #21262d; transition: 0.3s ease; border-left: 4px solid #30363d; }
        .result-item:hover { border-left-color: #58a6ff; box-shadow: 0 8px 16px rgba(0,0,0,0.6); transform: translateX(5px); }
        .result-url { font-size: 12px; color: #8b949e; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .result-title { font-size: 20px; color: #58a6ff; text-decoration: none; display: block; margin-bottom: 8px; font-weight: 600; }
        .result-title:hover { text-decoration: underline; color: #79c0ff; }
        .result-snippet { font-size: 14.5px; color: #c9d1d9; line-height: 1.6; white-space: pre-wrap; }
        
        /* Efek Neon untuk Kata yang Cocok */
        mark.keyword-highlight { background: rgba(255, 123, 114, 0.2); color: #ff7b72; padding: 0 3px; border-radius: 3px; font-weight: bold; box-shadow: 0 0 5px rgba(255, 123, 114, 0.4); }
        
        .similarity-score { display: inline-block; margin-top: 12px; font-size: 12px; color: #3fb950; font-weight: bold; padding: 5px 10px; background: rgba(63, 185, 80, 0.1); border: 1px solid rgba(63, 185, 80, 0.3); border-radius: 20px; }
    </style>
</head>
<body>

    <div class="google-header">
        <div class="logo-text">UUD</div>
        <form id="search-form" class="search-box">
        <input type="text" id="cari" class="search-input" placeholder="Cari isi pasal (misal: perlindungan, kesehatan)..." autocomplete="off">
        
        <select id="filter_bab" class="filter-select" title="Pilih Kategori Bab">
        <option value="all">🌍 Semua Bab</option>
        <option value="BAB I">🇮🇩 BAB I (Bentuk & Kedaulatan)</option>
        <option value="BAB II">🏛️ BAB II (MPR)</option>
        <option value="BAB III">👑 BAB III (Kekuasaan Pemerintahan)</option>
        <option value="BAB V">🏢 BAB V (Kementerian Negara)</option>
        <option value="BAB VI">🗺️ BAB VI (Pemerintahan Daerah)</option>
        <option value="BAB VII">🗣️ BAB VII (DPR, DPD, Pemilu)</option>
        <option value="BAB VIII">💰 BAB VIII (Keuangan & BPK)</option>
        <option value="BAB IX">⚖️ BAB IX (Kekuasaan Kehakiman)</option>
        <option value="BAB X">👥 BAB X & XA (Warga Negara & HAM)</option>
        <option value="BAB XI">🕌 BAB XI (Agama)</option>
        <option value="BAB XII">🛡️ BAB XII (Pertahanan & Keamanan)</option>
        <option value="BAB XIII">🎓 BAB XIII (Pendidikan & Kebudayaan)</option>
        <option value="BAB XIV">🌾 BAB XIV (Perekonomian & Kesejahteraan)</option>
        <option value="BAB XV">🚩 BAB XV (Bendera, Bahasa, Lambang)</option>
        <option value="BAB XVI">📝 BAB XVI (Perubahan UUD)</option>
        <option value="UMUM">📌 Aturan Peralihan / Tambahan</option>
        </select>
        
        <select id="rank" class="filter-select">
            <option value="5">Top 5</option>
            <option value="10" selected>Top 10</option>
            <option value="50">Top 50</option>
        </select>
        
        <button type="submit" id="search" class="search-btn"><i class="fas fa-search"></i></button>
    </form>
    </div>

    <div class="results-container">
        <div id="loading" class="d-none text-info mb-3"><i class="fas fa-circle-notch fa-spin"></i> Memindai database dengan TF-IDF...</div>
        <div id="content"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search-form").submit(function(e){
                e.preventDefault();
                var cari = $("#cari").val();
                var rank = $("#rank").val();
                var filter = $("#filter_bab").val();

                if(cari.trim() == "") return;

                // 1. Munculkan loading dan bersihkan hasil lama
                $("#content").empty();
                $("#loading").removeClass('d-none');

                $.ajax({
                    url: '/search',
                    type: 'GET',
                    cache: false, // Mencegah browser menyimpan cache lama
                    data: { q: cari, rank: rank, filter: filter },
                    dataType: "json",
                    success: function(data){
                        // 2. MATIKAN LOADING SAAT DATA SUDAH DATANG
                        $("#loading").addClass('d-none');
                        
                        // Tangkap pesan error dari Python (jika ada)
                        if(data && data.length > 0 && data[0].bab.includes("ERROR")) {
                            $('#content').html(`<div class="result-item border-danger"><h4 class="text-danger">${data[0].pasal}</h4><pre class="text-warning">${data[0].isi}</pre></div>`);
                            return;
                        }

                        if(data && data.length > 0) {
                            let searchTerms = cari.trim().split(/\s+/).filter(word => word.length > 0);
                            let regexHighlight = new RegExp("(" + searchTerms.join("|").replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ")", "gi");

                            $.each(data, function(key, item) {
                                let teksIsi = item.isi ? String(item.isi) : "Tidak ada isi pasal.";
                                
                                if(searchTerms.length > 0) {
                                    teksIsi = teksIsi.replace(regexHighlight, "<mark class='keyword-highlight'>$1</mark>");
                                }

                                let resultHtml = `
                                    <div class="result-item" style="animation: fadeIn 0.5s ease forwards;">
                                        <div class="result-url"><i class="fas fa-book-open"></i> Undang-Undang Dasar 1945 &rsaquo; ${item.bab}</div>
                                        <a href="#" class="result-title">${item.pasal}</a>
                                        <div class="result-snippet">${teksIsi}</div>
                                        <div class="similarity-score"><i class="fas fa-chart-line"></i> TF-IDF Score: ${parseFloat(item.score).toFixed(4)}</div>
                                    </div>`;
                                $('#content').append(resultHtml);
                            });
                        } else {
                            $('#content').html('<div class="result-item"><div class="result-snippet text-center text-danger">Tidak ada pasal yang relevan dengan kata kunci tersebut.</div></div>');
                        }
                    },
                    error: function(xhr, status, error){
                        // 3. MATIKAN LOADING JUGA JIKA TERJADI ERROR AJAX
                        $("#loading").addClass('d-none');
                        $('#content').html(`<div class="result-item border-danger"><h4 class="text-danger">AJAX Error: ${error}</h4><pre class="text-warning">${xhr.responseText}</pre></div>`);
                    }
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\SearchEngine\resources\views/landing.blade.php ENDPATH**/ ?>