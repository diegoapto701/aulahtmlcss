<?php
// ============================================================
// database.php
// Configuração e conexão com o banco de dados Neon (PostgreSQL)
// ============================================================

class Database {
    private static $instance = null;
    private $pdo;
    
    // Dados de conexão - Neon PostgreSQL
    private $host = 'ep-steep-grass-aycw03y2-pooler.c-5.us-east-2.aws.neon.tech';
    private $port = '5432';
    private $dbname = 'neondb';
    private $user = 'neondb_owner';
    private $password = 'npg_vQhO8yH6Dptk';
    
    private function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};sslmode=require;channel_binding=require";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            
            // Verifica/ cria a tabela 'contatos' com ID 1
            $this->initializeTable();
            
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
    
    // Singleton pattern
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    private function initializeTable() {
        try {
            // Verifica se a tabela 'contatos' existe
            $stmt = $this->pdo->query("SELECT to_regclass('public.contatos')");
            $exists = $stmt->fetchColumn();
            
            if (!$exists) {
                // Cria a tabela 'contatos'
                $this->pdo->exec("
                    CREATE TABLE contatos (
                        id SERIAL PRIMARY KEY,
                        nome VARCHAR(200) NOT NULL,
                        telefone VARCHAR(30) NOT NULL,
                        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )
                ");
                
                // Insere o registro com ID 1 (conforme solicitado)
                $this->pdo->exec("
                    INSERT INTO contatos (nome, telefone) 
                    VALUES ('Usuário Inicial', '(11) 99999-9999')
                ");
                
                error_log("Tabela 'contatos' criada com ID 1 inicial");
            }
            
        } catch (PDOException $e) {
            error_log("Erro ao inicializar tabela: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function insertContato($nome, $telefone) {
        try {
            $sql = "INSERT INTO contatos (nome, telefone) VALUES (:nome, :telefone)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':telefone' => $telefone
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir contato: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para listar todos os contatos (opcional, para testes)
    public function listarContatos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contatos ORDER BY id");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao listar contatos: " . $e->getMessage());
            return [];
        }
    }
}

// Não é necessário instanciar aqui, será feito no index.php
?>
