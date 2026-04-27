<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT c.*, u.prenom, u.no, u.email 
    FROM commande c 
    JOIN utilisateur u ON c.id_client = u.id 
    ORDER BY c.date_commande DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - Commandes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 20px;
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto; 
            background: white; 
            padding: 25px 30px; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* DASHBOARD NAV - BIEN ALIGNÉ */
        .dashboard-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3498db;
            align-items: center;
        }
        .nav-btn {
            padding: 10px 22px;
            background: #ecf0f1;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: 2px solid transparent;
            white-space: nowrap;
        }
        .nav-btn:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        .nav-btn.active {
            background: #3498db;
            color: white;
            border-color: #2980b9;
        }
        .nav-btn.logout {
            margin-left: auto;
            background: #e74c3c;
            color: white;
        }
        .nav-btn.logout:hover { 
            background: #c0392b;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        h1 { 
            color: #2c3e50; 
            margin-bottom: 20px;
            font-size: 26px;
        }
        
        /* STATS CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }
        .stat-card {
            padding: 18px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-card h3 { font-size: 30px; margin-bottom: 5px; }
        .stat-card p { opacity: 0.9; font-size: 13px; }
        .stat-total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-attente { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-validee { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-livree { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

        /* TABLE */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th { 
            background: #34495e; 
            color: white; 
            padding: 14px 12px; 
            text-align: left; 
            font-weight: 600;
            font-size: 14px;
        }
        td { 
            padding: 14px 12px; 
            border-bottom: 1px solid #ecf0f1; 
            font-size: 14px;
        }
        tr:hover { background: #f8f9fa; }
        
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }
        .badge-En_attente { background: #f39c12; color: white; }
        .badge-validee { background: #27ae60; color: white; }
        .badge-livree { background: #3498db; color: white; }
        
        /* ACTIONS - BIEN ALIGNÉES */
        .actions-cell {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
        }
        .btn-details {
            background: #3498db;
            color: white;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }
        .btn-details:hover { 
            background: #2980b9; 
            transform: translateY(-1px);
        }
        .status-form {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .status-form select {
            padding: 6px 10px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            background: white;
            min-width: 110px;
        }
        .status-form select:focus {
            outline: none;
            border-color: #3498db;
        }
        .btn-ok {
            background: #27ae60;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: 0.3s;
            white-space: nowrap;
        }
        .btn-ok:hover { 
            background: #229954; 
            transform: translateY(-1px);
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .nav-btn.logout { margin-left: 0; }
            .actions-cell { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- DASHBOARD NAVIGATION -->
        <div class="dashboard-nav">
            <a href="admin_commandes.php" class="nav-btn active">📋 Commandes</a>
            <a href="liste_article.php" class="nav-btn active">📦 Gérer Articles</a>
            <a href="liste_utilisateur.php" class="nav-btn active">👥 Gérer Utilisateurs</a>
            <a href="logout.php" class="nav-btn logout">🚪 Déconnexion</a>
        </div>

        <h1>📊 Dashboard - Gestion des Commandes</h1>
        
        <?php
        // Calcul des stats
        $total_cmd = $result->num_rows;
        $result->data_seek(0);
        
        $stmt_attente = $conn->prepare("SELECT COUNT(*) as nb FROM commande WHERE statut = 'En_attente'");
        $stmt_attente->execute();
        $nb_attente = $stmt_attente->get_result()->fetch_assoc()['nb'];
        
        $stmt_validee = $conn->prepare("SELECT COUNT(*) as nb FROM commande WHERE statut = 'validee'");
        $stmt_validee->execute();
        $nb_validee = $stmt_validee->get_result()->fetch_assoc()['nb'];
        
        $stmt_livree = $conn->prepare("SELECT COUNT(*) as nb FROM commande WHERE statut = 'livree'");
        $stmt_livree->execute();
        $nb_livree = $stmt_livree->get_result()->fetch_assoc()['nb'];
        ?>

        <!-- STATS -->
        <div class="stats">
            <div class="stat-card stat-total">
                <h3><?php echo $total_cmd; ?></h3>
                <p>Commandes totales</p>
            </div>
            <div class="stat-card stat-attente">
                <h3><?php echo $nb_attente; ?></h3>
                <p>En attente</p>
            </div>
            <div class="stat-card stat-validee">
                <h3><?php echo $nb_validee; ?></h3>
                <p>Validées</p>
            </div>
            <div class="stat-card stat-livree">
                <h3><?php echo $nb_livree; ?></h3>
                <p>Livrées</p>
            </div>
        </div>
        
        <!-- TABLE COMMANDES -->
        <table>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php while($cmd = $result->fetch_assoc()): ?>
            <tr>
                <td><strong>#<?php echo $cmd['id_commande']; ?></strong></td>
                <td>
                    <?php echo $cmd['prenom'] . ' ' . $cmd['no']; ?><br>
                    <small style="color:#7f8c8d;"><?php echo $cmd['email']; ?></small>
                </td>
                <td><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                <td><strong><?php echo number_format($cmd['prix_total'], 0, ',', ' '); ?> FCFA</strong></td>
                <td>
                    <span class="badge badge-<?php echo $cmd['statut']; ?>">
                        <?php echo str_replace('_', ' ', $cmd['statut']); ?>
                    </span>
                </td>
                <td>
                    <div class="actions-cell">
                        <a href="details_commande.php?id=<?php echo $cmd['id_commande']; ?>" class="btn-details">👁️ Détails</a>
                        
                        <form action="update_statu.php" method="POST" class="status-form">
                            <input type="hidden" name="id_commande" value="<?php echo $cmd['id_commande']; ?>">
                            <select name="statut">
                                <option value="En_attente" <?php if($cmd['statut']=='En_attente') echo 'selected'; ?>>En attente</option>
                                <option value="validee" <?php if($cmd['statut']=='validee') echo 'selected'; ?>>Validée</option>
                                <option value="livree" <?php if($cmd['statut']=='livree') echo 'selected'; ?>>Livrée</option>
                            </select>
                            <button type="submit" class="btn-ok">OK</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>