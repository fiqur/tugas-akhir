<!-- Content Header (Page header) -->
<section class="content-header">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css') ?>">
   <script type="text/javascript">
    $(document).ready(function(){
        if($("#select_toko").val() != 0 && $("#select_gender").val() != 0 && $("#select_kategori").val() != 0){
            $("#field_simpan").show();
        }
    });

    function showGrafik() {
        $("#panel-grafik").show();
        var toko = $("#select_toko").val();
        var gender = $("#select_gender").val();
        var kategori = $("#select_kategori").val();
        $.getJSON("<?php echo base_url('index.php/home/viewTrend?select-toko=')?>"+toko+"&select-gender="+gender+"&select-kategori="+kategori+"&jenis=tahunan", function(json) {
            var tahun = json[0].time;

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'grafik',
                    type: 'line'
                },
                title: {
                    text: 'Toko ' + toko + ' - Kategori ' + kategori
                },
                subtitle: {
                    text: '(Per Tahun)'
                },
                xAxis: {
                    categories: tahun
                },
                yAxis: {
                    title: {
                        text: 'Total Poin Ulasan'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    formatter: function() {
                        return 'Tahun = '+ this.x +'<br/>'+'<b> Jumlah = ' + this.y + ' Poin</b>';
                    }
                },
                series: [{
                    "name": "Jumlah Poin Ulasan Per Tahun",
                    "data": json[0].data
                }]
            })
        });
    }

    function showGrafikBulanan() {
        $("#panel-grafik").show();
        var toko = $("#select_toko").val();
        var gender = $("#select_gender").val();
        var kategori = $("#select_kategori").val();
        var tahun = $("#select_tahun").val();
        $.getJSON("<?php echo base_url('index.php/home/viewTrend?select-toko=')?>"+toko+"&select-gender="+gender+"&select-kategori="+kategori+"&jenis=bulanan"+"&tahun="+tahun, function(json) {
            console.log(json);

            if(json[0] == null){
                alert("Data tidak ditemukan");
            }else {
                var bulan = json[0].time;

                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'grafik',
                        type: 'line'
                    },
                    title: {
                        text: 'Toko ' + toko + ' - Kategori ' + kategori
                    },
                    subtitle: {
                        text: '(Per Bulan pada Tahun ' + tahun + ')'
                    },
                    xAxis: {
                        categories: bulan
                    },
                    yAxis: {
                        title: {
                            text: 'Total Poin Ulasan'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function () {
                            return 'Bulan ' + this.x + '<br/>' + '<b> Jumlah = ' + this.y + ' Poin</b>';
                        }
                    },
                    series: [{
                        "name": "Jumlah Poin Ulasan Per Bulan pada Tahun " + tahun,
                        "data": json[0].data
                    }]
                })
            }
        });
    }

    function showGrafikInterval() {
        $("#panel-grafik").show();
        var toko = $("#select_toko").val();
        var gender = $("#select_gender").val();
        var kategori = $("#select_kategori").val();

        if($("#start").val() != "" && $("#end").val() != ""){
            var start = $("#start").val();
            var end = $("#end").val();

            $.getJSON("<?php echo base_url('index.php/home/viewTrend?select-toko=')?>"+toko+"&select-gender="+gender+"&select-kategori="+kategori+"&jenis=interval"+"&start="+start+"&end="+end, function(json) {
                var bulan = json[0].time;

                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'grafik',
                        type: 'line'
                    },
                    title: {
                        text: 'Toko ' + toko + ' - Kategori ' + kategori
                    },
                    subtitle: {
                        text: '(Per Bulan pada Interval Tanggal ' + start + ' sampai ' + end + ')'
                    },
                    xAxis: {
                        categories: bulan
                    },
                    yAxis: {
                        title: {
                            text: 'Total Poin Ulasan'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function() {
                            return 'Bulan '+ this.x +'<br/>'+'<b> Jumlah = ' + this.y + ' Poin</b>';
                        }
                    },
                    series: [{
                        "name": "Jumlah Poin Ulasan Per Bulan pada Interval " + start + " sampai " + end,
                        "data": json[0].data
                    }]
                })
            });
        }else {
            alert("Masukkan Tanggal Filter dengan Benar!");
        }
    }

    function showByYear() {
        $("#bulanan").show();
        $("#interval").hide();
    }

    function showByInterval() {
        $("#bulanan").hide();
        $("#interval").show();
    }
   </script>

   <script type="text/javascript">
       $(function(){
         $.ajaxSetup({
         type:"POST",
         url: "<?php echo base_url('index.php/home/ambil_data') ?>",
         cache: false,
         });

         $("#select_toko").change(function(){
             $("#panel-grafik").hide();
             $("#bulanan").hide();
             $("#interval").hide();
           if($("#select_toko").val() != 0 && $("#select_gender").val() != 0 && $("#select_kategori").val() != 0){
              $("#field_simpan").show();
           }else {
             $("#field_simpan").hide();
           }
           var value=$(this).val();
           if(value!=0){
             $.ajax({
               data:{modul:'gender',lapak:value},
               success: function(respond){
                 $("#select_gender").html(respond);
               }
             })
           }
         });

         $("#select_gender").change(function(){
           $("#panel-grafik").hide();
             $("#bulanan").hide();
             $("#interval").hide();
           if($("#select_toko").val() != 0 && $("#select_gender").val() != 0 && $("#select_kategori").val() != 0){
             $("#field_simpan").show();
           }else {
             $("#field_simpan").hide();
           }
           var value=$(this).val();
           var lapak=$("#select_toko").val();
           if(value!=0){
             $.ajax({
               data:{modul:'kategori',lapak:lapak,gender:value},
               success: function(respond){
                 $("#select_kategori").html(respond);
               }
             })
           }
         });

         $("#select_kategori").change(function(){
           $("#panel-grafik").hide();
             $("#bulanan").hide();
             $("#interval").hide();
           if($("#select_toko").val() != 0 && $("#select_gender").val() != 0 && $("#select_kategori").val() != 0){
             $("#field_simpan").show();
           }else {
             $("#field_simpan").hide();
           }
         });

         var rb = document.getElementById('rb1');
         rb.onclick = handler;

         function handler() {
             $("#bulanan").hide();
             $("#interval").hide();

             showGrafik();
         }
       });
   </script>
</section>

<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div align="center" class="panel-heading" data-toggle="collapse" data-target="#collapse1">Temporal Projection</div>
        <div class="panel-body" id="collapse1">
            <div class='col-md-12'>
                <form  class="form-inline">
                  <div class="form-group col-md-7" id="pilih_toko" style="margin-bottom: 20px">
                    <label class="col-md-3" for="select_toko">Pilih Toko  </label>
                    <select id="select_toko" class="form-control" name="select-toko">
                      <option value="0">-- Pilih Toko --</option>
                      <?php
                      foreach ($toko as $lapaks) {
                        $save = ($lapaks == $lapak) ? 'selected' : '';
                        echo '<option value="' . $lapaks .  '"' . $save . '>' . $lapaks . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-md-7" id="pilih_gender" style="margin-bottom: 20px">
                    <label class="col-md-3" for="select_gender">Pilih Gender  </label>
                    <select id="select_gender" class="form-control" name="select-gender">
                      <option value="0">-- Pilih Gender --</option>
                    </select>
                  </div>
                  <div class="form-group col-md-7" id="pilih_kategori" style="margin-bottom: 20px">
                    <label class="col-md-3" for="select_kategori">Pilih Kategori  </label>
                    <select id="select_kategori" class="form-control" name="select-kategori">
                      <option value="0">-- Pilih Kategori --</option>
                    </select>
                  </div>
                  <div class="form-group col-md-7" id="field_simpan" style="display: none">
                    <label class="col-md-3"></label>
                    <input type="button" class="btn btn-primary" value="Cari" onclick="showGrafik()" />
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div style="width: 100%;">
        <div class="panel panel-primary" id="panel-grafik" style="display: none">
            <div class="panel-body">
                <div class='col-md-12'>
                    <div class="form-group">
                        <label class="col-md-1">Time: </label>
                        <label class="radio-inline"><input type="radio" name="optradio" id="rb1">By All Year</label>
                        <label class="radio-inline"><input type="radio" name="optradio" onclick="showByYear()">By Year</label>
                        <!-- <label class="radio-inline"><input type="radio" name="optradio" onclick="showByInterval()">By Interval</label> -->
                    </div>
                    <div class="col-md-2" id="bulanan" style="display: none">
                        <label>Pilih Tahun: </label>
                        <select class="form-control" name="tahun" id="select_tahun" onchange="showGrafikBulanan()">
                            <option value="0">Please Select</option>
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= ($thn_skr-10); $x--) {
                                ?>
                                <option value="<?php echo $x ?>"><?php echo $x ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="interval" style="display: none">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>From</label>
                                <div class="input-group date">
                                    <input type="date" data-format="YYYY-MM-dd" id="start" class="form-control"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Until</label>
                                <div class='input-group date'>
                                    <input type="date" data-format="YYYY-MM-dd" id="end" class="form-control"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 24px">
                                <a class="btn btn-primary" onclick="showGrafikInterval()">
                                    <span class="glyphicon glyphicon-filter"></span> Filter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="grafik"></div>
            </div>
        </div>
    </div>
</section>

<!--tambahkan custom js disini-->
<script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js') ?>" type="text/javascript"></script>