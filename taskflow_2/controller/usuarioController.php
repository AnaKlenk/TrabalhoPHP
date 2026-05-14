<?php
namespace App\Controller;
use App\Util\Functions as Util;

class usuarioController {
    public static ?string $msg = null;
    public static ?string $msgTipo = null;
    private static string $arqUsuarios = __DIR__ . '/../db_usuarios.json';

    public static function listarTodos(): array {
        if (!file_exists(self::$arqUsuarios)) return [];
        return json_decode(file_get_contents(self::$arqUsuarios), true) ?? [];
    }

    public static function cadastrar(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarios = self::listarTodos();
            $email = Util::preparaTexto($_POST['email'] ?? '');

            foreach ($usuarios as $u) {
                if ($u['email'] === $email) {
                    self::$msg = "E-mail já cadastrado!";
                    self::$msgTipo = 'erro';
                    return;
                }
            }

            $usuarios[] = [
                'id' => empty($usuarios) ? 1 : max(array_column($usuarios, 'id')) + 1,
                'nome' => Util::preparaTexto($_POST['nome'] ?? ''),
                'email' => $email,
                'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
            ];

            file_put_contents(self::$arqUsuarios, json_encode($usuarios, JSON_PRETTY_PRINT));
            self::$msg = "Cadastro realizado! Faça login.";
            self::$msgTipo = 'sucesso';
        }
    }

    public static function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarios = self::listarTodos();
            $email = Util::preparaTexto($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            foreach ($usuarios as $u) {
                if ($u['email'] === $email && password_verify($senha, $u['senha'])) {
                    $_SESSION['usuario_id'] = $u['id'];
                    $_SESSION['usuario_nome'] = $u['nome'];
                    $_SESSION['db_usuarios'] = $usuarios;

                    if (isset($_POST['lembrar'])) {
                        setcookie('ultimo_email', $email, time() + (86400 * 30), '/');
                    } else {
                        setcookie('ultimo_email', '', time() - 3600, '/');
                    }
                    header("Location: ?p=home");
                    exit;
                }
            }
            self::$msg = "E-mail ou senha incorretos.";
            self::$msgTipo = 'erro';
        }
    }
}