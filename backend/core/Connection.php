<?php

namespace Core;

use Exception;
use PDO;
use RuntimeException;

final class Connection
{
    private static ?PDO $connection = null;

    /**
     * Singleton: Método construtor privado para impedir classe de gerar instâncias
     */
    private function __construct()
    {

    }

    private function __clone()
    {

    }

    /**
     * Método estático privado que permite o carregamento do arquivo
     * @param string $file
     * @return array
     * @throws Exception
     */
    private static function load(string $file): array
    {
        $file = __DIR__ . '/../config/' . $file . '.ini';
        if (file_exists($file)) {
            $dados = parse_ini_file($file);
        } else {
            throw new RuntimeException('Erro: Arquivo não encontrado');
        }
        return $dados;
    }

    /**
     * Método montar string de conexao e gerar o objeto PDO
     * @param $dados array
     * @return PDO
     * @throws Exception
     */
    private static function make(array $dados): PDO
    {
        $sgdb = $dados['sgdb'] ?? null;
        $usuario = $dados['usuario'] ?? null;
        $senha = $dados['senha'] ?? null;
        $banco = $dados['banco'] ?? null;
        $servidor = $dados['servidor'] ?? null;
        $porta = $dados['porta'] ?? null;

        if (!is_null($sgdb) && !is_null($usuario) && !is_null($senha) && !is_null($banco) && !is_null($servidor)) {
            // selecionar banco - criar string de conexão
            switch (strtoupper($sgdb)) {
                case 'MYSQL' :
                    $porta = $porta ?? 3306;
                    return new PDO("mysql:host={$servidor};port={$porta};dbname={$banco}", $usuario, $senha);
                case 'MSSQL' :
                    $porta = $porta ?? 1433;
                    return new PDO("mssql:host={$servidor},{$porta};dbname={$banco}", $usuario, $senha);
                case 'PGSQL' :
                    $porta = $porta ?? 5432;
                    return new PDO("pgsql:dbname={$banco}; user={$usuario}; password={$senha}, host={$servidor};port={$porta}");
                case 'SQLITE' :
                    return new PDO("sqlite:{$banco}");
                case 'OCI8' :
                    return new PDO("oci:dbname={$banco}", $usuario, $senha);
                case 'FIREBIRD' :
                    return new PDO("firebird:dbname={$banco}", $usuario, $senha);
                default:
                    throw new RuntimeException('Erro: tipo de banco de dados não suportado');
            }
        } else {
            throw new RuntimeException('Erro: tipo de banco de dados não informado, ou faltam informações no arquivo de configuração');
        }
    }

    /**
     * Método estático que devolve a instância ativa
     * @param string $file
     * @return PDO
     * @throws Exception
     */
    public static function getInstance(string $file): PDO
    {
        if (self::$connection === null) {
            // Receber os dados do arquivo
            self::$connection = self::make(self::load($file));
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->exec("set names utf8");
        }
        return self::$connection;
    }
}
