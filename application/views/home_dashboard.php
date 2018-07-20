<link href="https://fonts.googleapis.com/css?family=Pacifico&subset=latin-ext,vietnamese" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700&subset=latin-ext,vietnamese" rel="stylesheet">

<style>
    #table_new {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table_new td, #table_new th {
        border: 0px solid #ddd;
        padding: 8px;
    }

    #table_new tr:nth-child(even){background-color: #f2f2f2;}

    #table_new tr:hover {background-color: #ddd;}

    #table_new th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #3c8dbc;
        color: white;
    }

    .zoom {
        transition: transform .2s; /* Animation */
        margin: 0 auto;
    }

    .zoom:hover {
        transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container" style="margin-left: 5px; margin-top: 30px; ">
        <form class="form-inline" action="<?php echo base_url('index.php/home/addDataInsert');?>" method="post">
            <select name="gender" class="form-control"  required>
                <option>Gender...</option>
                <option value="pria">Pria</option>
                <option value="wanita">Wanita</option>
            </select>
            <select name="kategori" class="form-control"  required>
                <option>Kategori...</option>
                <option value="kaos">Koas</option>
                <option value="kemeja">Kemeja</option>
                <option value="polo">Polo</option>
                <option value="jaket">Jaket</option>
                <option value="sweater">Sweater</option>
                <option value="jeans">Jeans</option>
                <option value="jilbab">Jilbab</option>
                <option value="baju muslim">Baju Muslim</option>
                <option value="celana kain">Celana Kain</option>
            </select>
            <input type="text" class="form-control" name="lapak" placeholder="Nama Lapak...">

           <div class="input-group custom-search-form">
                    <input type="text" class="form-control" name="url_site" style="width: 500px;" placeholder="Link...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit" name="submit"><span class=" " aria-hidden="true"><span style="margin-left:10px;">Crawl</span></button>
                    </span>
            </div>
        </form>
    </div>

</section>

<!-- Main content -->
<section class="content">

        <div class="row" style="margin-left: 20px; margin-top: 20px; margin-right: 10px">
            <table id="table_new">
                <thead>
                <tr>
                    <th width="10%">Gambar</th>
                    <th width="30%">Nama Barang</th>
                    <th width="10%">Gender</th>
                    <th width="10%">Kategori</th>
                    <th width="10%">Harga</th>
                    <th width="10%">Skor</th>
                    <th width="10%">Link</th>
                    <th width="10%">View</th>
                </tr>
                </thead>
                <tbody>

                <?php
                    foreach($barang as $u) {
                        ?>

                        <tr>
                            <td><img class="zoom" src="<?php echo $u->url_img ?>"  width="100px" height="100px"></td>
                            <td><?php echo $u->nama_barang ?></td>
                            <td><?php echo $u->gender ?></td>
                            <td><?php echo $u->kategori ?></td>
                            <td><?php echo $u->harga ?></td>
                            <td><?php echo $u->total_score ?></td>
                            <td style="alignment: center">
                                <a href="<?php echo $u->url_site ?>" target="_blank">
                                    <button class="btn btn-success right"> Go to link</button>
                                </a>
                            </td>
                            <td style="alignment: center">
                                <a href="<?php echo base_url('index.php/home/feedback?id_barang=');?><?=$u->id_barang?>">
                                    <button class="btn btn-warning right"> View Comments</button>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
</section>