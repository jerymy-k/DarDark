<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Session.php';

class User
{
    private PDO $db;
    public ?string $name;
    public string $email;
    private ?string $password;
    public ?string $role;
    public function __construct(?string $name, string $email, ?string $password, ?string $role)
    {
        $this->db = Database::getInstance()->getConnection();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    public function signUp(): bool
    {
        $name = $this->name;
        $email = $this->email;
        $password = $this->password;
        $role = $this->role;
        if (self::emailExist($email)) {
            return false;
        }
        $passHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(name , email , password , role ) VALUES (?,?,?,?) ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $passHash, $role]);
    }
    public function logIn(): bool
    {
        $email = $this->email;
        $password = $this->password;
        if ($user = self::emailExist($email)) {
            $userPass = $user['password'];
            if (password_verify($password, $userPass)) {
                Session::set('id', $user['id']);
                Session::set('role', $user['role']);
                return true;
            } else {
                Session::set('errer', "Incorrect password.");
                return false;
            }
        }
        Session::set('errer', "We couldn’t find an account with this email.");
        return false;
    }
    public function editProfile(int $id): bool
    {
        $name = $this->name;
        $email = $this->email;
        $password = $this->password;
        if ($password) {
            $password = password_hash($password , PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ? , email = ? , password = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $email, $password, $id]);
        } else {
            $sql = "UPDATE users SET name = ? , email = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $email, $id]);
        }
        return true;
    }
    private static function emailExist(string $email)
    {
        $conne = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conne->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    public static function passwordCheaker(string $email, string $password)
    {
        $conne = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conne->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }
    public static function getUserById(int $id): ?array
    {
        $conne = Database::getInstance()->getConnection();
        $sql = "SELECT name , email , created_at FROM users WHERE id = ?";
        $stmt = $conne->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}