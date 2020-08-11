<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model{

	const SESSION = "User"; 
	public static function login($login, $password)
	{


		$sql = new Sql();

		$results = $sql->select("Select * From tb_users where deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		if (count($results)===0)
		{
			throw new \Exception("Usuario inexistente ou senha inválida."); // a "\" resfere a exceção do namespace principal do php , e não dentro do Hcode/model
		}

		$data = $results[0];

		if (password_verify($password, $data["despassword"]) === true)
		{

			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();
			
			return $user;
		
		} else {
			throw new \Exception("Usuario inexistente ou senha inválida.");
			
		}
	}

	public static function verifyLongin($inadmin = true)
	{
		
		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"]>0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin

		){

				header("Location: /admin/login");
				exit;
			
		}
	}

	public static function logout(){

		$_SESSION[User::SESSION] = Null;
	}

	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("Select * From tb_users a INNER JOIN tb_persons b USING(idperson) Order BY b.desperson");

	}

	


	public function save()
	{	
		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);
	
	}


	public function get($iduser)
	{
		$sql = new Sql();

		$results = $sql->select("Select * From tb_users a INNER JOIN tb_persons b USING(idperson) Where a.iduser = :iduser", array(			":iduser"=>$iduser
		));

		$this->setData($results[0]);
	}

	public function update(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
			"iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);
	}

	public function delete(){

		$sql = new Sql();

		$sql->query("CALL sp_users_delete(:iduser)",array(
			":iduser"=>$this->getiduser()
		));
	}
}
?>