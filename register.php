<?php
    include "./config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="./js/jquery.js"></script>
    <title>Register</title>
</head>
<body>
    
    <div class="bgcolor">
        
        <div class="fullcontainer">

            <div class="formside">
                <div>
                    <h2>Do you have account already?</h2>
                    <pre>Click Login for login</pre>
                </div>
               
                <a href="./login.php">Login</a>
            </div>

            <div class="formcontent">
                <div>
                    <h1>Register</h1>
                    <pre>Register with social network</pre>
                    <div class="sns">
                        <a href="https://www.instagram.com" target="_blank" class="icons"><i class="fab fa-instagram"></i></a>
                        <a href="http://linkedin.com" target="_blank" class="icons"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://twitter.com" target="_blank" class="icons"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com" target="_blank" class="icons"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div id="">
                    <hr width="100px;" style="display: inline-block;vertical-align: text-bottom;">OR<hr width="100px;" style="display: inline-block;vertical-align: text-bottom;">
                </div>
                <br>
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>" class="regiserform">
                    <div class="register_element basic_info">
                        <input  class="register_BasicInput" name="fname" id="fname" type="text" placeholder="First Name" required>
                    </div>
                    <div class="register_element basic_info">
                        <input  class="register_BasicInput" name="lname" id="lname" type="text" placeholder="Last Name" required>
                    </div>
                    <div class="register_element basic_info">
                        <input  class="register_BasicInput" name="email" id="email" type="text" placeholder="Email" required>
                    </div>
                    <div class="register_element basic_info">
                        <input  class="register_BasicInput" name="password" id="pass" type="password" placeholder="Passoword" required>
                        <span id="span1" class="passwordicon"><i id="pass1" class="fas fa-eye"></i><i id="pass2" class="fas fa-eye-slash"></i></span>
                    </div>
                    <div class="register_element basic_info">
                        <input  class="register_BasicInput" name="confirm" id="confirm" type="password" placeholder="Confirm" required>
                        <span id="span2" class="passwordicon"><i id="pass3" class="fas fa-eye"></i><i id="pass4" class="fas fa-eye-slash"></i></span>
                    </div>
                    <div class="register_element" style="width: 170px;">
                        <span>Remember me</span>
                        <input style="background-color: rgba(250, 196, 210, 0.76);" name="rememberme" type="checkbox">
                    </div>
                    <div style="width: 100%;text-align:center;">
                        <input type="submit" value="Register">
                    </div>    
                </form>

            </div>

        </div>

    </div>

    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try{
                $con=connect_db();
                if($con->connect_error){
                    throw new Exception("Connect failed",0);
                }
                $checkcmd="SELECT user_id,email,password,salt FROM login_register_tb WHERE email='".$_POST["email"]."'";
                $checkquery=$con->query($checkcmd);
                if($checkquery->num_rows>0){
                    throw new Exception("This username is already exist");
                }
                $salt=salting();
                $tmppass=md5($_POST["password"].$salt);
                $insert=$con->prepare("INSERT INTO login_register_tb (email,password,fname,lname,salt) VALUES (?,?,?,?,?)");
                $insert->bind_param("sssss",$_POST["email"],$tmppass,$_POST["fname"],$_POST["lname"],$salt);
                $insert->execute();
                $insert->close();
                $con->close();

                if(isset($_POST["rememberme"]) && $_POST["rememberme"] == true){
                    cookie($_POST["email"],$_POST["password"]);
                }
                //when refresh after post added in db why??
                }
                catch(Exception $ex){
                    echo $ex->getMessage()."<br>";
                }
            }
    ?>

    <script>
        $("window").add(function(){
            $("#pass1").hide();
            $("#pass3").hide();
           
            $("#span1").on("click",function(e){
                $("#pass1").toggle();
                $("#pass2").toggle();
                console.log($("#pass").attr("type"))
                if($("#pass").attr("type")=="password"){
                    console.log("1");
                    $("#pass").attr("type","text");
                }
                else if($("#pass").attr("type")=="text"){
                    $("#pass").attr("type","password");
                    console.log("2");
                }
            });
            $("#span2").on("click",function(e){
                $("#pass3").toggle();
                $("#pass4").toggle();
                if($("#confirm").attr("type")=="password"){
                    console.log("1");
                    $("#confirm").attr("type","text");
                }
                else if($("#confirm").attr("type")=="text"){
                    $("#confirm").attr("type","password");
                    console.log("2");
                }
            });
        });

    </script>

    
</body>
</html>