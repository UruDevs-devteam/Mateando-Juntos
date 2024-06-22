<?php
class Post
{
  private $conex;
  public function __construct($conn)
  {
    $this->conex = $conn;
  }
  public function GetPosts()
  {
    $query = "SELECT * FROM Post";
    $result = mysqli_query($this->conex, $query);
    $Posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $Posts[] = $row;
    }
    return $Posts;
  }
  public function GetPostByID($id)
  {
    $query = "SELECT * FROM Post WHERE ID_post = $id";
    $result = mysqli_query($this->conex, $query);
    $Post = mysqli_fetch_assoc($result);
    return $Post;
  }

  public function DeletePost($id){
    $ID = $id['Id'];
    $query ="DELETE FROM Post WHERE ID_post = $ID";
    $result = mysqli_query($this->conex, $query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function AddPost($data)
  {
    $titulo= $data['Titulo'];
    $descripcion = $data['Descripcion'];
    $fecha = $data['Feacha'];
    $query = "INSERT INTO Post VALUES (NULL ,'$titulo' , '$descripcion' , '$fecha')";
    $result = mysqli_query($this->conex, $query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function AddMulti($data) {
    $numero = $data['numero'];
		$post = $data['Id'];
    $src = $data['src'];
    $query = "INSERT INTO Post_img VALUES ('$post','$numero','$src')";
    $result = mysqli_query($this->conex, $query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

}