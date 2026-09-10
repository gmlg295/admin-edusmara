<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EDUSMARA</title>
    <link rel="icon" href="https://smantigque.id/public/bank/images/icon/icon-smantig-7.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('public/bank/css/main-style.css') ?>">
    <?php 
    if (isset($load_css)) { ?>
    <?php if (is_array($load_css)) { ?>
        <?php for ($a = 0; $a < count($load_css); $a++) { ?>
        <link rel="stylesheet" href="<?= base_url('public/bank/css/'. $load_css[$a]) ?>">
        <?php }
    } else { ?>
        <link rel="stylesheet" href="<?= base_url('public/bank/css/'. $load_css)?>">
    <?php }
    } ?>

</head>
<body>