<?php 
session_start();
require "include/connect.php";
include "include/logic.php";

if(isset($_COOKIE['likes'])) {
  $dataInput = !empty($_COOKIE['likes']) ? trim($_COOKIE['likes']) : null;
  $data = htmlspecialchars($dataInput, ENT_QUOTES, 'UTF-8');
} else {
  $data = "";
}


//pagination
$limit = 12;
//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY date DESC");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);
$total_results = $palettePull->rowCount();
$total_pages = ceil($total_results/$limit);
    
if (!isset($_GET['page'])) {
    $page = 1;
} else{
    $page = $_GET['page'];
}

$start = ($page-1)*$limit;

$stmt = $pdo->prepare("SELECT * FROM palette ORDER BY date DESC LIMIT $start, $limit");
$stmt->execute();

// set the resulting array to associative
$stmt->setFetchMode(PDO::FETCH_OBJ);
    
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
$conn = null;

// var_dump($results);
$no = $page > 1 ? $start+1 : 1;

$i = 0;
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$url?>css/main.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <meta name="description" content="Check out new block palettes submitted by the Minecraft community. Get building inspiration or create and share your own block palettes">
    
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Block Palettes - New Block Palettes For Minecraft Builders</title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81969207-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-81969207-1');
    </script>

  </head>
  <body>
    <!-- Nav -->
    <div class="topbar" data-toggle="modal" data-target="#exampleModalCenter">
      <div class="container">
        <div class="topbarText">
          NEW SITE - Click Here To Find Out More
        </div> 
      </div>
    </div>
    <?php foreach($palette as $c): ?>
        <?php 
            $id = (string)$c["id"];
          
        ?>
        <?php if (strpos($data, $id) == true) {?>
            <?php 
              $i++ 
            ?>
        <?php } else {} ?>
    <?php endforeach; ?>
    <div class="custom-header" id="#">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?=$url?>">
                    <img src="<?=$url?>img/logotest.png" class="logo-size">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="images/hamburger-solid.svg" width="35px">
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav ml-auto custom-nav-text centeredContent">
                      <li class="nav-item">
                            <a href="<?=$url?>" class="nav-link">Featured Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>new" class="nav-link">New Palettes<div class="active"></div></a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>create" class="nav-link btn btn-theme-nav">Create</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- End Nav -->
    <div class="palettes">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="title">New Palettes</div>
          </div>
          <?php foreach($results as $p): ?>
          <div class="col-lg-4 col-md-6 paddingFix">
            <div style="position: relative">
              <div class="palette-float">
                <a href="<?=$url?>palette/<?=$p['id']?>">
                  <img src="<?=$url?>img/block/<?=$p['blockOne']?>.png" class="block">
                  <img src="<?=$url?>img/block/<?=$p['blockTwo']?>.png" class="block">
                  <img src="<?=$url?>img/block/<?=$p['blockThree']?>.png" class="block">
                  <img src="<?=$url?>img/block/<?=$p['blockFour']?>.png" class="block">
                  <img src="<?=$url?>img/block/<?=$p['blockFive']?>.png" class="block">
                  <img src="<?=$url?>img/block/<?=$p['blockSix']?>.png" class="block">
                </a>
                <div class="subtext">
                  <?php if($p['featured'] == 1) { ?>
                  <div class="award half">
                    <i class="fas fa-award"></i> Staff Pick
                  </div>
                  <div class="time half">
                    <?=time_elapsed_string($p['date'])?>
                  </div>
                  <?php } else { ?>
                  <div class="time" style="float:right">
                    <?=time_elapsed_string($p['date'])?>
                  </div>
                    <?php } ?>
                  </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a href="<?=$url?>new" class="page-link"><i class="fas fa-chevron-double-left"></i></a></li>
            <?php for($p=1; $p<=$total_pages; $p++){?> 
            <li class="<?= $page == $p ? 'active' : ''; ?> page-item"><a href="<?=$url?><?= 'new/'.$p; ?>" class="page-link"><?= $p; ?></a></li>
            <?php }?>
            <li class="page-item"><a href="<?=$url?>new/<?= $total_pages; ?>" class="page-link"><i class="fas fa-chevron-double-right"></i></a></li>
        </ul> 
    </nav>

    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="medium-title" id="exampleModalLongTitle">Updates</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="small-title">Welcome to the NEW and IMPROVED Block Palettes!</h4>
            <p>As you can see a few things have changed from the previous site.</p>
            <ul>
            <li>You can now create palettes in real time! Head over to the <a href="<?=$url?>/create">create</a> page and create a beautiful block palette.</li>
            <li>We are still curating palettes. On the <a href="<?=$url?>">featured</a> page our staff picks 12 users submitted palettes every week to be apart of the collection!</li>
            <li>We have created an <a href="https://www.instagram.com/blockpalettes/">Instagram</a> where we will post daily palettes from the <a href="<?=$url?>/new">new palettes</a> page.</li>
            </ul>
            <p>This is just the beginning with this new platform. We have many great updates on the way that will continue to improve the site into the future!</p>
            <p>Thank you for the support!<br><i>- Block Palettes Staff</i></p>
          </div>    
        </div>
      </div>
    </div>
    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>