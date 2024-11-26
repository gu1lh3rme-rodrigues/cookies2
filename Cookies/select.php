<?php
session_start(); // Inicia a sessão ou retoma a sessão existente

// Verifica se o usuário está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php"); // Redireciona para a página de login se não estiver autenticado
    exit(); // Garante que o redirecionamento ocorra e o script pare aqui
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['produtos'] as $produto => $valor) {
        setcookie($produto, $valor, time() + 20);
    }
    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Produtos</title>
</head>

<body>
    <nav>
        <div class="navbar">
            <div class="logo">
                <img src="assets/icon.png" alt="">
            </div>
            <h1>Impressoras</h1>
        </div>
    </nav>
    <main>
        <h1>Nossas Impressoras</h1>
        <?php
        // Conexão com o banco de dados
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "ironfit";

        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        // Consulta para buscar os produtos
        $sql = "SELECT idProduto, nomeProduto, valor, nomeImagem FROM produto";
        $result = $conn->query($sql);
        ?>

        <form method="POST" action="select.php">
            <div id="produtos">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="produto">
                            <img src="assets/<?php echo htmlspecialchars($row['nomeImagem']); ?>"
                                alt="<?php echo htmlspecialchars($row['nomeProduto']); ?>">
                            <p><?php echo htmlspecialchars($row['nomeProduto']); ?></p>
                            <input type="checkbox" name="produtos[<?php echo htmlspecialchars($row['idProduto']); ?>]"
                                value="<?php echo htmlspecialchars($row['valor']); ?>">
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Nenhum produto encontrado.</p>";
                }
                ?>
            </div>
            <div class="button-container">
                <button type="submit">Adicionar ao Carrinho</button>
            </div>
        </form>

        <?php
        $conn->close();
        ?>
        <div class="logout">
            <a href='logout.php'>Logout</a>
        </div>
    </main>
</body>

</html>