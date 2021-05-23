<?php
include './../layouts/session.php';
$init = $pdo->open();
$return = $_SERVER['HTTP_REFERER'];

if(isset($_POST['add-category'])) {
    $category = $_POST['category'];

    $sql = $init->prepare("SELECT * FROM category WHERE categoryName=:categoryName ");
    $sql->execute(['categoryName' => $category]);

    if ($sql->rowCount() > 0) {
        $_SESSION['error'] = 'Category already exits';
    } else {

        $sql = $init->prepare("INSERT INTO category(categoryName) VALUES (:categoryName)");
        $sql->execute(['categoryName'=>$category]);
        $_SESSION['success'] = 'Category added successfully';
    }
    header('Location: '.$return);
}

if(isset($_POST['add-book'])) {
    $book = $_POST['book'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $shelve = $_POST['shelve'];
    $price= $_POST['price'];
    $quantity= $_POST['quantity'];

    $sql = $init->prepare("SELECT * FROM book WHERE bookName=:bookName ");
    $sql->execute(['bookName' => $book]);

    if ($sql->rowCount() > 0) {
        $_SESSION['error'] = 'Book already exits';
    } else {

        $sql = $init->prepare("INSERT INTO book(bookName, categoryID,quantity,author, shelveNumber, price) 
						VALUES (:bookName,:categoryID,:quantity,:author, :shelveNumber, :price)");
        $sql->execute(['bookName'=>$book, 'categoryID'=>$category,'quantity'=>$quantity,'author'=>$author, 'shelveNumber'=>$shelve, 'price'=>$price]);
        $_SESSION['success'] = 'Book added successfully';
    }
    header('Location: '.$return);
}

if(isset($_POST['edit-book'])) {
    $id = $_POST['edit-book'];
    $book = $_POST['book'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $shelve = $_POST['shelve'];
    $price= $_POST['price'];
    $quantity= $_POST['quantity'];

    $sql = $init->prepare("SELECT * FROM book WHERE bookName=:bookName ");
    $sql->execute(['bookName' => $book]);

    if ($sql->rowCount() < 0) {
        $_SESSION['error'] = 'Book does not exit';
    } else {

        try{
            $sql = $init->prepare("UPDATE book SET bookName=:bookName, categoryID=:categoryID, quantity=:quantity,
                                         author=:author,shelveNumber=:shelveNumber,price=:price
                                         WHERE id=:id");
            $sql->execute(['bookName'=>$book, 'categoryID'=>$category,'quantity'=>$quantity,'author'=>$author, 'shelveNumber'=>$shelve, 'price'=>$price,'id'=>$id]);
            $_SESSION['success'] = 'Book updated successfully';
        }catch (Exception $e){
            $_SESSION['error'] = $e->getMessage();
        }

    }
    header('Location: '.$return);
}

if(isset($_POST['delete-book'])){
    $id = $_POST['delete-book'];

    try{
        $sql = $init->prepare("DELETE FROM book WHERE id=:id");
        $sql->execute(['id'=>$id]);

        $_SESSION['success'] = 'Book deleted successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    header('Location: '.$return);

}

if (isset($_POST['getBook'])) {
    $id = $_POST['getBook'];

    $sql = $init->prepare("SELECT * FROM book WHERE id=:id");
    $sql->execute(['id' => $id]);
    $results = $sql->fetch();

    echo json_encode($results);
}

//student

if (isset($_POST['getStudent'])) {
    $studentNo = $_POST['getStudent'];

    $sql = $init->prepare("SELECT * FROM student WHERE studentNo=:studentNo");
    $sql->execute(['studentNo' => $studentNo]);
    $results = $sql->fetch();

    echo json_encode($results);
}

if(isset($_POST['edit-student'])) {
    $studNo = $_POST['edit-student'];
    $name = $_POST['edit-name'];
    $email = $_POST['edit-email'];
    $id_number = $_POST['edit-id_number'];
    $gender = $_POST['edit-gender'];
    $password= $_POST['edit-password'];

    $sql = $init->prepare("SELECT * FROM student WHERE studentNo=:studentNo ");
    $sql->execute(['studentNo' => $studNo]);

    if ($sql->rowCount() < 0) {
        $_SESSION['error'] = 'Student does not exit';
    } else {

        try{
            $sql = $init->prepare("UPDATE student SET name=:name, email=:email, id_number=:id_number,
                                         gender=:gender,password=:password
                                         WHERE studentNo=:studentNo");
            $sql->execute(['name'=>$name,'email'=>$email,'id_number'=>$id_number, 'gender'=>$gender, 'password'=>$password,'studentNo'=>$studentNo]);
            $_SESSION['success'] = 'Student updated successfully';
        }catch (Exception $e){
            $_SESSION['error'] = $e->getMessage();
        }

    }
    header('Location: '.$return);
}

if(isset($_POST['delete-student'])){
    $studentNo = $_POST['delete-student'];

    try{
        $sql = $init->prepare("DELETE FROM student WHERE studentNo=:studentNo");
        $sql->execute(['studentNo'=>$studentNo]);

        $_SESSION['success'] = 'Student deleted successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    header('Location: '.$return);

}








if (isset($_POST['user_id'])) {
    $id = $_POST['user_id'];

    $stmt = $conn->prepare("SELECT * FROM farmer WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    echo json_encode($row);
}

if (isset($_POST['farmer_id'])) {
    $id = $_POST['farmer_id'];

    $stmt = $conn->prepare("SELECT * FROM farmer WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    echo json_encode($row);
}

if (isset($_POST['admin_id'])) {
    $id = $_POST['admin_id'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    echo json_encode($row);
}

if (isset($_POST['profile_admin'])) {
    $id = $_SESSION['admin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM admin WHERE email=:email AND id <>:id");
    $stmt->execute(['email'=>$email, 'id'=>$id]);
    $row = $stmt->fetch();
    if($row['numrows'] > 0){
        $_SESSION['error'] = 'Email already exits';
    }
    else {

        $stmt = $conn->prepare("UPDATE admin SET email=:email, password=:password, firstName=:name,
                                         mobile=:mobile
                                         WHERE id=:id");
        $stmt->execute(['email' => $email, 'password' => $password, 'name' =>
            $name, 'mobile' => $mobile,'id'=>$id]);

        $_SESSION['success'] = 'Record updated successfully';
    }
    header('location: welcome.php');
}

if (isset($_POST['profile_farmer'])) {
    $id = $_SESSION['admin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM admin WHERE email=:email AND id <>:id");
    $stmt->execute(['email'=>$email, 'id'=>$id]);
    $row = $stmt->fetch();
    if($row['numrows'] > 0){
        $_SESSION['error'] = 'Email already exits';
    }
    else {

        $stmt = $conn->prepare("UPDATE admin SET email=:email, password=:password, firstName=:name,
                                         mobile=:mobile
                                         WHERE id=:id");
        $stmt->execute(['email' => $email, 'password' => $password, 'name' =>
            $name, 'mobile' => $mobile,'id'=>$id]);

        $_SESSION['success'] = 'Record updated successfully';
    }
    header('location: welcome.php');
}

if (isset($_POST['edit_admin'])) {
    $id = $_POST['edit_admin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM admin WHERE email=:email AND id <>:id");
    $stmt->execute(['email'=>$email, 'id'=>$id]);
    $row = $stmt->fetch();
    if($row['numrows'] > 0){
        $_SESSION['error'] = 'Email already exits';
    }
    else {

        $stmt = $conn->prepare("UPDATE admin SET email=:email, password=:password, name=:name,
                                         mobile=:mobile
                                         WHERE id=:id");
        $stmt->execute(['email' => $email, 'password' => $password, 'name' =>
            $name, 'mobile' => $mobile,'id'=>$id]);

        $_SESSION['success'] = 'Record updated successfully';
    }
    header('location: welcome.php');
}


if (isset($_POST['edit_farmer'])) {
    $id = $_POST['edit_farmer'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM farmer WHERE email=:email AND id <>:id");
    $stmt->execute(['email'=>$email, 'id'=>$id]);
    $row = $stmt->fetch();
    if($row['numrows'] > 0){
        $_SESSION['error'] = 'Email already exits';
    }
    else {

        $stmt = $conn->prepare("UPDATE farmer SET email=:email, password=:password, firstName=:firstname,
                                         lastName=:lastname,gender=:gender,mobile=:mobile,address=:address
                                         WHERE id=:id");
        $stmt->execute(['email' => $email, 'password' => $password, 'firstname' =>
            $firstname, 'lastname' => $lastname, 'gender' => $gender,'mobile' => $mobile, 'address' => $address,'id'=>$id]);

        $_SESSION['success'] = 'Record updated successfully';
    }
    header('location: welcome.php');
}

if (isset($_POST['search'])) {

    $_SESSION['search']=$_POST['search'];
    header('Location: welcome.php');
}

if(isset($_POST['addStudent'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM farmer,admin WHERE farmer.email=:email OR admin.email=:email");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();
    if ($row['numrows'] > 0) {
        $_SESSION['error'] = 'Email already exits';
    } else {

        $stmt = $conn->prepare("INSERT INTO farmer (firstName, lastName,gender, mobile, address, email, password) 
						VALUES (:firstName, :lastName,:gender, :mobile, :address, :email,:password)");
        $stmt->execute(['firstName'=>$firstname, 'lastName'=>$lastname, 'gender'=>$gender,'mobile'=>$mobile, 'address'=>$address, 'email'=>$email, 'password'=>$password]);
        $userid = $conn->lastInsertId();
        $_SESSION['success'] = 'Book added successfully';

    }
    header('Location: '.$return);
}



if(isset($_POST['addAdmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM farmer,admin WHERE farmer.email=:email OR admin.email=:email");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();
    if ($row['numrows'] > 0) {
        $_SESSION['error'] = 'Email already exits';
    } else {

        $stmt = $conn->prepare("INSERT INTO admin (name, mobile, email, password) 
						VALUES (:name, :mobile, :email,:password)");
        $stmt->execute(['name'=>$name,'mobile'=>$mobile, 'email'=>$email, 'password'=>$password]);
        $userid = $conn->lastInsertId();
        $_SESSION['success'] = 'Admin added successfully';

    }
    header('Location: welcome.php');
}

if(isset($_POST['decline'])){
    $id = $_POST['decline'];

    try{
        $stmt = $conn->prepare("UPDATE space SET status_id=1 WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Record approved successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    header('Location: '.$_SERVER['HTTP_REFERER']);

}

if(isset($_POST['livestockID'])){
    $id = $_POST['livestockID'];

    try{

        $stmt = $conn->prepare("SELECT * FROM livestock WHERE farmer_id=:id");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetchAll();

    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    echo json_encode($row);

}

if(isset($_POST['farmerID'])){
    $id = $_POST['farmerID'];

    try{

        $stmt = $conn->prepare("SELECT * FROM farmer WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch();

    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    echo json_encode($row);

}

if(isset($_POST['report'])){
    $report = $_POST['report'];

    try{

        if($report =='admins'){
            $stmt = $conn->prepare("SELECT * FROM admin");
            $stmt->execute();
            $row = $stmt->fetchAll();
        }else{
            $stmt = $conn->prepare("SELECT * FROM farmer");
            $stmt->execute();
            $row = $stmt->fetchAll();
        }

    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    echo json_encode($row);

}

if(isset($_POST['farmer_delete'])){
    $id = $_POST['farmer_delete'];

    try{
        $stmt = $conn->prepare("DELETE FROM farmer WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Farmer deleted successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    header('Location: welcome.php');

}

if(isset($_POST['admin_delete'])){
    $id = $_POST['admin_delete'];

    try{
        $stmt = $conn->prepare("DELETE FROM admin WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Admin deleted successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }
    header('Location: welcome.php');

}


$pdo->close();

?>