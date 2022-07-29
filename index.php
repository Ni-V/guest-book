<?php
session_start();
require 'includes/config.php';
 
$user_ip_address = $_SERVER['REMOTE_ADDR']; 
$user_agent = $_SERVER['HTTP_USER_AGENT']; 

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$results_per_page = 25;
$start_from = ($page-1) * $results_per_page;
 
if (isset ($_POST['send'])){

$un=($_POST['username']);
$eml=($_POST['email']);
$homepage=($_POST['homepage']);
$message=($_POST['message']);
  if(($un=="") || ($eml=="") || ($message=="")){
    $errors="Ошибка! Проверьте поля";
  }

if (!(preg_match("/([A-Za-z0-9]+)/", $un))) {
    $errors="Ошибка! Проверьте User Name";
}   

$eml = filter_var($eml, FILTER_SANITIZE_EMAIL);  
if (!filter_var($eml, FILTER_VALIDATE_EMAIL)) {
    $errors="Ошибка! Проверьте EMail";
} 

if (($homepage!="") && !(filter_var($homepage, FILTER_VALIDATE_URL))) {
    $errors="Ошибка! Проверьте Homepage";
}

 if(isset($_POST["captcha_code"])){        
    if(!($_POST["captcha_code"] === $_SESSION["captcha_code"])){
         $errors="Ошибка!";
    }
  }

$message = strip_tags($message);

if (empty($errors)){

mysqli_query($con, "INSERT INTO `comments`(`name`, `email`, `homepage`, `text`, `created`, `ip`, `browser`) VALUES ('{$_POST['username']}','{$_POST['email']}','{$_POST['homepage']}','{$_POST['message']}', NOW(), '$user_ip_address', '$user_agent')");
header("Refresh:0");
}
else{
  echo ($errors);
}
}

 ?>
<?php require 'includes/header.php'; ?>
 
   <?php if (isset($_SESSION["success"])): ?>
     <div class="error success">
       <h3>
         <?php echo $_SESSION["success"];
         unset($_SESSION["success"]);
         ?>
       </h3>
     </div>
   <?php endif; ?>
 
 <div class="header">
   <h2>ГОСТЕВАЯ КНИГА</h2>   
   <p>отфильтровать по:</p>
   <form action="" method="post">
        <input type="submit" name="name" value="User Name"/>
        <input type="submit" name="em_l" value="Email"/>
        <input type="submit" name="created_asc" value="Date (по возрастанию)"/>
        <input type="submit" name="created_desc" value="Date (по убыванию)"/>
    </form>
 </div>
 
 <div class="book-content">
<?php
    if(isset($_POST['name'])) {
    $comments=mysqli_query($con,"select * from `comments` order by `name` limit $start_from, $results_per_page");
  } else if(isset($_POST['em_l']))
  {
    $comments=mysqli_query($con,"select * from `comments` order by `email` limit $start_from, $results_per_page");
  } else if(isset($_POST['created_asc'])) {
    $comments=mysqli_query($con,"select * from `comments` order by `created` asc limit $start_from, $results_per_page");
  } else if(isset($_POST['created_desc'])) {
    $comments=mysqli_query($con,"select * from `comments` order by `created` desc limit $start_from, $results_per_page");
  } else {
    $comments=mysqli_query($con,"select * from `comments` limit $start_from, $results_per_page");
  }

    while($connect=mysqli_fetch_assoc($comments)){?>
    <div class="comments">
      <table>
        <tr>
            <th>User Name</th>
            <th>EMail</th>
            <th>Homepage</th>
            <th>Date</th>
            <th>Text</th>
        </tr>
          <tr><td><?=$connect['name']?></td>
          <td><?=$connect['email']?></td>
          <td><?=$connect['homepage']?></td>
          <td><?=$connect['created']?></td>
          <td><?=$connect['text']?></td></tr>

        
      </table>
  </div>
  <?php } 
    $sql = "SELECT count(*) AS total FROM `comments`";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();

    $total_pages = ceil($row["total"] / $results_per_page);

    for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
              echo "<a href='index.php?page=".$i."'";
              if ($i==$page)  echo " class='curPage'";
              echo ">".$i."</a> ";
}; ?>
 </div>

 <section >
  <div class="container_comments">
    <div class="comments-error"><p><?$errors?></p></div>
    
      <form action="" method="post">
        <h1>СООБЩЕНИЯ</h1><br>
        <input type="text" name="username" placeholder="User Name">
        <input type="text" name="email" placeholder="EMail">
        <input type="text" name="homepage" placeholder="Homepage">
        <img src="includes/captcha_gen.php" />
        <input type="text" name="captcha_code" class="cptch" autocomplete="off" placeholder="CAPTCHA">
        <textarea type="text" name="message" placeholder="Text"></textarea>
        <input type="submit" name="send">
      </form>



      </div>  
</section>