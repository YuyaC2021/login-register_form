<!--
    //when refresh after post added in db why ??
    //why sometime css doesn't change ??
    //how to check cookie ??
-->

<?php
    include "./config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="./js/jquery.js"></script>
    <title>Login</title>
</head>
<body>

    <div class="bgcolor">

        <div class="fullcontainer">

            <div class="formcontent">
                <div>
                    <h1>Login</h1>
                    <pre>Login with social network</pre>
                    <div class="sns">
                        <a href="https://www.instagram.com" target="_blank" class="icons"><i class="fab fa-instagram"></i></a>
                        <a href="http://linkedin.com" target="_blank" class="icons"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://twitter.com" target="_blank" class="icons"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com" target="_blank" class="icons"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <br>
                <div>
                    <hr width="100px;" style="display: inline-block;vertical-align: text-bottom;">OR<hr width="100px;" style="display: inline-block;vertical-align: text-bottom;">
                </div>
                <br>
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>"" class="loginform">
                    <div class="login_element basic_info">
                        <input name="userinfo"  class="login_BasicInput" id="emailorstuid" type="text" placeholder="Email" 
                        value="<?php if(isset($_COOKIE["Username"])){echo $_COOKIE["Username"];} ?>" required>
                    </div>
                    <div class="login_element basic_info">
                        <input name="pass" class="login_BasicInput" id="pass" type="password" placeholder="Passoword" 
                        value="<?php if(isset($_COOKIE["Password"])){echo $_COOKIE["Password"];} ?>"  required>
                        <span id="span1" class="passwordicon"><i id="icon1" class="fas fa-eye"></i><i id="icon2" class="fas fa-eye-slash"></i></span>
      
                    </div>
                    <div class="login_element">
                        <span>Remember me</span>
                        <input style="background-color: rgba(250, 196, 210, 0.76);" name="rememberme" type="checkbox">
                    </div>
                    <input type="submit" value="Log in">
                </form>

            </div>
            <div class="formside">
                <div>
                    <h2>Are you new here?</h2>
                    <pre>Click Sign up for registration</pre>
                </div>
                <a href="./register.php">Sign up</a>                
            </div>

        </div>

    </div>

    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            echo "s";
            $con=connect_db();
            try{
                if($con->connect_error){
                    throw new Exception("Connect failed",0);
                }
                $checkcmd="SELECT salt FROM login_register_tb WHERE user_id='".$_POST["userinfo"]."' OR email='".$_POST["userinfo"]."'";
                $checkquery=$con->query($checkcmd);
                if($checkquery->num_rows>0){   
                    while($row=$checkquery->fetch_assoc()){
                        $salt=$row["salt"];
                    }
                    $tmppass=md5($_POST["pass"].$salt);
                    $checkcmd="SELECT * FROM login_register_tb WHERE password='".$tmppass."'";
                    $checkquery=$con->query($checkcmd);
                    echo "<br>".$salt."<br>";
                    echo "<br>$tmppass<br>";
                    echo print_r($checkquery->num_rows);
                    if($checkquery->num_rows>0){
                        echo "login";
                        if(isset($_POST["rememberme"]) && $_POST["rememberme"] == true){
                            cookie($_POST["userinfo"],$_POST["pass"]);
                            echo "cookie"; 
                        }
                    }
                    $con->close();
                }
            
                else{
                    throw new Exception("Wrong information");
                }
            }catch(Exception $ex){
                echo $ex->getMessage()."<br>";
            }
        }
    ?>

    <script>
        $(window).on("load",function(){
            $("#icon1").hide();
            $("#span1").on("click",function(){
                $("#icon1").toggle();
                $("#icon2").toggle();
                if($("#pass").attr("type")=="password"){
                    $("#pass").attr("type","text");
                }
                else if($("#pass").attr("type")=="text"){
                    $("#pass").attr("type","password");
                }
            });

        });
    </script>
</body>
</html>
