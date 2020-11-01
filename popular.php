<?php 
session_start();
require "include/connect.php";
require "include/logic.php";


if(isset($_COOKIE['likes'])) {
  $dataInput = !empty($_COOKIE['likes']) ? trim($_COOKIE['likes']) : null;
  $data = htmlspecialchars($dataInput, ENT_QUOTES, 'UTF-8');
} else {
  $data = "";
}

//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY likes DESC");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Block Palettes - Minecraft Building Inspiration Through Blocks</title>
  </head>
  <body>
    <!-- Nav -->
    <div class="topbar">
      <div class="container">
        <div class="topbarText">
          A Message For All To Hear
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
                <a class="navbar-brand" href="#">
                    <img src="img/logotest.png" class="logo-size">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="images/hamburger-solid.svg" width="35px">
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav ml-auto custom-nav-text centeredContent">
                      <li class="nav-item">
                            <a href="popular" class="nav-link">Popular Palettes<div class="active"></div></a>
                        </li>
                        <li class="nav-item">
                            <a href="new" class="nav-link">New Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="saved" class="nav-link" id="closeNav" >Saved Palettes <span class="saved"><?=$i?></span></a>
                        </li>
                        <li class="nav-item">
                            <a href="create" class="nav-link btn btn-theme-nav">Create</a>
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
            <div class="title">Popular Palettes</div>
          </div>
          <?php foreach($palette as $p): ?>
          <div class="col-lg-4 col-md-6 paddingFix">
            <div style="position: relative">
              <a href="palette/<?=$p['id']?>">
                <div class="palette-float">
                  <img src="img/block/<?=$p['blockOne']?>.png" class="block">
                  <img src="img/block/<?=$p['blockTwo']?>.png" class="block">
                  <img src="img/block/<?=$p['blockThree']?>.png" class="block">
                  <img src="img/block/<?=$p['blockFour']?>.png" class="block">
                  <img src="img/block/<?=$p['blockFive']?>.png" class="block">
                  <img src="img/block/<?=$p['blockSix']?>.png" class="block">
                  <div class="subtext">
                    <div class="likes half">
                    <?php 
                      $id = (string)$p["id"];
                    ?>
                    <?php if (strpos($data, $id) == true) {?>
                      <form method="post" action="popular" style="margin-bottom:0px">
                        <input type="hidden" name="id" value="<?=$p['id']?>">
                        <button type="submit" name="unlike" class="btn-like"><i class="fas fa-heart liked"></i> <?=$p['likes']?></button>
                      </form>
                    <?php } else { ?>
                      <form method="post" action="popular" style="margin-bottom:0px">
                        <input type="hidden" name="id" value="<?=$p['id']?>">
                        <button type="submit" name="like" class="btn-like"><i class="far fa-heart"></i> <?=$p['likes']?></button>
                      </form>
                    <?php } ?>
                    </div>
                    <div class="time half">
                      <?=time_elapsed_string($p['date'])?>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->

  </body>
</html>