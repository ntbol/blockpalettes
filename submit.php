<?php
error_reporting(0);
session_start();

include "include/logic.php";


if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else if(isset($_COOKIE['user_logged'])) { 
  $_SESSION['user_id'] = $_COOKIE['user_logged'];
  $_SESSION['logged_in'] = time();
} else {
    header('Location:' . $url);
}


$dir = "img/block/*.png";
//get the list of all files with .jpg extension in the directory and safe it in an array named $images
$images = glob( $dir );

if(isset($_SESSION['blockError'])) {
    $blockError = "You have entered in an invalid block!";
    unset($_SESSION['blockError']);
} elseif(isset($_SESSION['blockDup'])) {
    $blockError = "You have entered too many of the same blocks";
    unset($_SESSION['blockDup']);
} else {
    $blockError = "";
}


//extract only the name of the file without the extension and save in an array named $find
if(isset($_SESSION['error'])) {
  $error = "This palette already exists!";
  unset($_SESSION['error']);
} else {
  $error = "";
}
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
    <title>Block Palettes - Create a Minecraft Block Palette</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <meta name="description" content="Create a Minecraft block palette and share it to the community.">
  	<meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script>
        $(document).ready(function () {
          $('select').selectize({
              sortField: 'text'
          });
      });
    </script>
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
    <?php include('include/header.php'); ?>
    <?php if ($user['verified'] == 0){?>
    <div class="palettes">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="title" style="padding-bottom:15px">Submit Palette</div>
                    Your account must be verified to post palettes! Please check your email for an email from us :)<br>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="title" style="padding-bottom:15px">Submit Palette</div>
        
              <?php if($error != null) { ?>
                <div class="delete-tag" style="margin-bottom:25px"><?=$error?><?=$error?></div>
                
              <?php } else {?>
                <div style="margin-bottom:10px"></div>
              <?php } ?>
              <?php if($blockError != null) { ?>
                  <div class="delete-tag" style="margin-bottom:25px"><?=$blockError?></div>

              <?php } else {?>
                  <div style="margin-bottom:10px"></div>
              <?php } ?>
          </div>
          <div class="col-xl-6 col-lg-12 col-md-12 paddingFixLargeCreate">
            <div style="position: relative">
              <div class="palette-float-large">
                <div class="flex-thirds">
                  <img id="image1" src="img/placeholder.png" class="block">
                  <img id="image2" src="img/placeholder.png" class="block">
                  <img id="image3" src="img/placeholder.png" class="block">
                </div>
                <div class="flex-thirds">
                  <img id="image4" src="img/placeholder.png" class="block">
                  <img id="image5" src="img/placeholder.png" class="block">
                  <img id="image6" src="img/placeholder.png" class="block">
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-12 col-md-12 insertBlocks" style="padding-bottom:100px">
            <h2 class="medium-title">Pick Blocks</h2>
            <form method="post" method="submit">
              <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block One
                  <select id="select-1" name="block-one" class="form-control" placeholder="Search a block..." required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block Two
                  <select id="select-2" class="form-control" placeholder="Search a block..." name="block-two" required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");
                        echo $cleanStr . "<br>";

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block Three
                  <select id="select-3" class="form-control" placeholder="Search a block..." name="block-three" required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");
                        echo $cleanStr . "<br>";

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block Four
                  <select id="select-4" class="form-control" placeholder="Search a block..." name="block-four" required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");
                        echo $cleanStr . "<br>";

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block Five
                  <select id="select-5" class="form-control" placeholder="Search a block..." name="block-five" required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");
                        echo $cleanStr . "<br>";

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12 form-group">
                  Block Six
                  <select id="select-6" class="form-control" placeholder="Search a block..." name="block-six" required>
                    <option value="">Select a block...</option>
                    <?php 
                      foreach( $images as $image ):
                        $extCut = str_replace(".png","","$image");
                        $cleanStr = str_replace("img/block/","","$extCut");
                        echo $cleanStr . "<br>";

                        $blockName = str_replace("_"," ",$cleanStr);
                    ?>
                    <option value="img/block/<?=$cleanStr?>.png"><?=ucwords($blockName)?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-xl-12 form-group">
                  <?php if(isset($_SESSION['last_submit']) && ((time() - $_SESSION['last_submit']) < 60)) {
                     $time = time() - $_SESSION['last_submit'];
                    echo "You must wait 60 seconds, before submitting again. (" . $time . " seconds)";
                  } else { ?>
                  <button type="submit" name="create" class="btn btn-theme-form btn-block">Submit</button>
                  <?php } ?>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

    

    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
    <script>
        function set1() {
            var img = document.getElementById("image1");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-1").onchange = set1;

        function set2() {
            var img = document.getElementById("image2");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-2").onchange = set2;

        function set3() {
            var img = document.getElementById("image3");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-3").onchange = set3;

        function set4() {
            var img = document.getElementById("image4");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-4").onchange = set4;

        function set5() {
            var img = document.getElementById("image5");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-5").onchange = set5;

        function set6() {
            var img = document.getElementById("image6");
            img.src = this.value;
            return false;
        }
        document.getElementById("select-6").onchange = set6;
    </script>

    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>

  </body>
</html>