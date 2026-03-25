<?php
    function getNews($pdo, $limit = null) {
        $sql = "SELECT * FROM news ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getNewsById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getPrograms($pdo) {
        $stmt = $pdo->query("SELECT * FROM programs ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getProgramById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getSchedule($pdo, $group = null, $dateFrom = null, $dateTo = null) {
        $sql = "SELECT * FROM schedule WHERE 1=1";
        $params = [];

        if ($group && $group !== 'all') {
            $sql .= " AND group_name = :group";
            $params[':group'] = $group;
        }
        if ($dateFrom) {
            $sql .= " AND date >= :dateFrom";
            $params[':dateFrom'] = $dateFrom;
        }
        if ($dateTo) {
            $sql .= " AND date <= :dateTo";
            $params[':dateTo'] = $dateTo;
        }
        $sql .= " ORDER BY date ASC, time ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function isAdmin() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    function redirectIfNotAdmin() {
        if (!isAdmin()) {
            header('Location: ' . SITE_URL . '/admin/login.php');
            exit;
        }
    }

    function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
?>