<?php
namespace App\Model;

class Usuario {
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;

    private function __construct() {}

    public static function criar(?int $id, string $nome, string $email, string $senha): static {
        $u = new static();
        $u->id = $id;
        $u->setNome($nome);
        $u->setEmail($email);
        $u->senha = $senha;
        return $u;
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }
    public function getSenha(): string { return $this->senha; }

    public function setNome(string $nome): void {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException("O nome é obrigatório.");
        }
        $this->nome = $nome;
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("E-mail inválido.");
        }
        $this->email = $email;
    }

    public function setSenha(string $senha): void {
        if (strlen($senha) < 6) {
            throw new \InvalidArgumentException("A senha deve ter no mínimo 6 caracteres.");
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }
}
