<?php
session_start();
include "connexion_base.php";

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM utilisateur ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Utilisateurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            padding: 30px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        .boutons {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            transition: 0.3s;
        }
        .btn-dashboard {
            background: #3498db;
        }
        .btn-dashboard:hover {
            background: #2980b9;
        }
        .btn-deconnexion {
            background: #e74c3c;
        }
        .btn-deconnexion:hover {
            background: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #34495e;
            color: white;
            padding: 15px;
            text-align: left;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .role {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }
        .role-admin {
            background: #e8f5e9;
            color: #27ae60;
        }
        .role-client {
            background: #e3f2fd;
            color: #3498db;
        }
        .btn-action {
            padding: 8px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-right: 8px;
            transition: 0.3s;
            color: white;
        }
        .btn-modifier {
            background: #3498db;
        }
        .btn-modifier:hover {
            background: #2980b9;
        }
        .btn-supprimer {
            background: #e74c3c;
        }
        .btn-supprimer:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Utilisateurs</h1>
        
        <div class="boutons">
            <a href="dashboard_admin.php" class="btn btn-dashboard">🏠 Dashboard</a>
            <a href="logout.php" class="btn btn-deconnexion">🚪 Se déconnecter</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date inscription</th>
                <th>Actions</th>
            </tr>
            <?php while($u = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($u['no']); ?></strong></td>
                <td><?php echo htmlspecialchars($u['prenom']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td>
                    <span class="role <?php echo $u['role'] == 'admin' ? 'role-admin' : 'role-client'; ?>">
                        <?php echo ucfirst($u['role']); ?>
                    </span>
                </td>
                <td><?php echo date('d/m/Y', strtotime($u['date_inscription'])); ?></td>
                <td>
                    <a href="modifier_utilisateur.php?id=<?php echo $u['id']; ?>" class="btn-action btn-modifier">✏️ Modifier</a>
                    <a href="supprimer_utilisateur.php?id=<?php echo $u['id']; ?>" class="btn-action btn-supprimer" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>