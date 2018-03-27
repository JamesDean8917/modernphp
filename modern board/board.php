<?php

class User {

/** @var object $pdo Copy of PDO connection */
		    private $pdo;
		    /** @var object of the logged in user */
		    private $user;
		    /** @var string error msg */
		    private $msg;
		    /** @var int number of permitted wrong login attemps */
		    private $permitedAttemps = 5;

#id

   public function dbConnect($conString, $user, $pass){
        if(session_status() === PHP_SESSION_ACTIVE){
            try {
                $pdo = new PDO($conString, $user, $pass);
                $this->pdo = $pdo;
                return true;
            }catch(PDOException $e) { 
                $this->msg = 'Connection did not work out!';
                return false;
            }
        }else{
            $this->msg = 'Session did not start.';
            return false;
        }
    }

    public function dbConnect($conString, $user, $pass){
        if(sesson_satus() === PHP_SESSION_ACTIVE){
            try{
                $pdo = new PDO($conString, $user, $pass);
                $this->pdo = $pdo;
            }
        }
    }


	public function login($email,$password){
        if(is_null($this->pdo)){
            $this->msg = 'Connection did not work out!';
            return false;
        }else{
            $pdo = $this->pdo;
            $stmt = $pdo->prepare('SELECT id, name, nicname, password, email, skill1, member_level FROM member WHERE email = :email limit 1');
			$stmt->bindParam(':email',$email, PDO::PARAM_STR );
            $stmt->execute();
            $user = $stmt->fetch();
			
            if(password_verify($password, $user['password'] )){
                  if($user['member_level'] == 10){
					 //  if($user['id'] <= $this->permitedAttemps){
                    $this->user = $user;
                    session_regenerate_id();
                    $_SESSION['user']['id'] = $user['id'];
					$_SESSION['user']['member_levelt'] = 'Administrator';
					$_SESSION['user']['member_level'] = $user['member_level'];
                    $_SESSION['user']['name'] = $user['name'];
                    $_SESSION['user']['nicname'] = $user['nicname'];
                    $_SESSION['user']['email'] = $user['email'];
                    $_SESSION['user']['skill1'] = $user['skill1'];
					$this->msg = 'Welcome Admistrator.';
                    return true;
                }else if($user['member_level'] == 2){
					$_SESSION['user']['id'] = $user['id'];
					$_SESSION['user']['member_levelt'] = 'Developers';
					$_SESSION['user']['member_level'] = $user['member_level'];
                    $_SESSION['user']['name'] = $user['name'];
                    $_SESSION['user']['nicname'] = $user['nicname'];
                    $_SESSION['user']['email'] = $user['email'];
                    $_SESSION['user']['skill1'] = $user['skill1'];
                    $this->msg = 'Welcome developers. ';
					
					return true;
                }else if($user['member_level'] == 1){
					$this->msg = 'Welcome normal users. ';
					$_SESSION['user']['member_levelt'] = 'Users';
					$_SESSION['user']['member_level'] = $user['member_level'];
					return true;
				}else{
					$this->msg = 'Welcome normal visitors. ';
					$_SESSION['user']['member_levelt'] = 'Visitors';
					return true;
				}
				
            }else{
                $this->msg = 'Invalid login information or the account is not activated.';
                return false;
            } 
        }
    }




    public function registration($userid , $pass, $email, $name, $nickname, $skill1, $skill2, $skill3){
		
        $pdo = $this->pdo;
        if($this->checkEmail($email)){
            $this->msg = 'This email is already taken.';
            return false;
        }
        if(!(isset($email) && isset($name) && isset($pass)  && filter_var($email, FILTER_VALIDATE_EMAIL))){
            $this->msg = 'Inesrt all valid requered fields ( userid, password, name, email, skill1 ).';
            return false;
        }

        $pass = $this->hashPass($pass);
        $confCode = $this->hashPass(date('Y-m-d H:i:s').$email);
        $stmt = $pdo->prepare('INSERT INTO member (user_id, password, name, nicname, email, skill1, skill2, skill3 ) VALUES (:userid, :password, :name, :nickname, :email, :skill1, :skill2, :skill3 )');
		$stmt->bindParam (':userid' , $userid , PDO::PARAM_STR );
		$stmt->bindParam (':password' , $pass , PDO::PARAM_STR );
		$stmt->bindParam (':name' , $name , PDO::PARAM_STR );
		$stmt->bindParam (':nickname' , $nickname , PDO::PARAM_STR );
		$stmt->bindParam (':email' , $email , PDO::PARAM_STR );
		$stmt->bindParam (':skill1' , $skill1 , PDO::PARAM_STR );
		$stmt->bindParam (':skill2' , $skill2 , PDO::PARAM_STR );
		$stmt->bindParam (':skill3' , $skill3 , PDO::PARAM_STR );
        if($stmt->execute()){
	
        }else{
            $this->msg = 'Inesrting a new user failed.';
            return false;
        }
    }


	public function databind(){
		alert('aa');

	}



	 private function hashPass($pass){
        return password_hash(1238917, PASSWORD_DEFAULT);
    }


	  public function printMsg(){
        print $this->msg;
    }


	private function checkEmail($email){
        $pdo = $this->pdo;
        $stmt = $pdo->prepare('SELECT id FROM member WHERE email = :email limit 1');
		$stmt->bindParam (':email' , $email, PDO::PARAM_STR );
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }


    public function memo($email){
        $pdo = $this->pdo;
        $stmt = $pdo->prepare('SELECT email from member where email = :email');
        $stmt->bindPram(':email', $email, PDO::PARAM_STR );
        $stmt->execute();
        
    }










}



?>


