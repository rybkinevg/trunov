<?php

function print_block($args)
{
?>
    <div class="block">
        <div class="block__header">
            <h2 class="block__title"><?= $args['title']; ?></h2>
            <span class="block__status"><?= $args['status']; ?></span>
        </div>
        <div class="container">
            <?php

            foreach ($args['forms'] as $form) {

            ?>

                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="<?= $form['action']; ?>" />
                        <h3 class="item__title"><?= $form['title']; ?></h3>
                        <div class="item__body">
                            <?= $form['desc']; ?>
                        </div>
                        <div class="item__btn">
                            <button class="button <?= ($form['title'] == 'Очистка') ? 'delete-import' : 'button-primary' ?>" type="submit">
                                <?= $form['btn']; ?>
                            </button>
                        </div>
                    </form>
                </div>

            <?php

            }

            ?>
        </div>
    </div>
<?php
}
?>

<div class="wrap">

    <h2><?php echo get_admin_page_title() ?></h2>

    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .block {
            padding: 20px;
            box-shadow: 0 0 1px #1e1e1e;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .block__header {
            display: flex;
            align-items: center;
        }

        .block__title {
            margin: 0;
        }

        .block__status {
            display: inline-block;
            padding: 5px 10px;
            box-shadow: 0 0 1px #1e1e1e;
            background-color: #fff;
            margin-left: 20px;
        }

        .item {
            display: flex;
            flex-direction: column;
            padding: 25px;
            box-shadow: 0 0 1px #1e1e1e;
            background-color: #f1f1f1;
        }

        .item form {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .item__body {
            margin: 20px 0;
            flex: 1 1 auto;
        }

        .item__body p {
            margin-top: 0;
        }

        .item__body p:last-child {
            margin-bottom: 0;
        }

        .item__done {
            font-weight: 600;
            text-decoration: underline;
        }

        .item__btn {
            text-align: right;
        }

        .item__btn .delete-import {
            background: #a00;
            border-color: #a00;
            box-shadow: none;
            color: #fff;
        }

        .item__btn .delete-import:hover {
            background: #FFF;
            color: black;
            border-color: #a00;
        }

        .item__title {
            margin: 0;
        }
    </style>

    <?php

    print_block(rybkinevg\trunov\Lawyers::page_block());
    print_block(rybkinevg\trunov\Services::page_block());
    print_block(rybkinevg\trunov\Posts::page_block());
    print_block(rybkinevg\trunov\Works::page_block());
    print_block(rybkinevg\trunov\Books::page_block());
    print_block(rybkinevg\trunov\Court::page_block());
    print_block(rybkinevg\trunov\Partners::page_block());
    print_block(rybkinevg\trunov\For_lawyer::page_block());
    print_block(rybkinevg\trunov\Media_columns::page_block());
    print_block(rybkinevg\trunov\SOS::page_block());

    ?>

</div>