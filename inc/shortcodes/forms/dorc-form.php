<?php
/*
    @package dorc
    @subpackage plugin form product
 */
?>
<div class="">

    <?php if (isset($postID) && is_numeric($postID)) : ?>
    <div class="alert alert-success alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        Orçamento enviado com sucesso!
    </div>
    <?php else : ?>
        <?php echo isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products']) || $no_products
            ? ''
            : '<p class="alert alert-info">Você deve adicionar alguns produtos antes de enviar o pedido de orçamento.</p>'
        ?>
    <?php endif; ?>

    <?php if ($valid->isValidPost() == false) : ?>
    <div class="alert alert-warning alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        O todos o campos devem ser preenchidos corretamentes
    </div>
    <?php endif; ?>

    <form id="dorcorc" action="<?php echo $current_url; ?>" method="POST" class="clearfix no-border-radius" >
        <?php if (isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products'])) : ?>
            <h4><?php _e('Produtos adicionados', 'dorc') ?></h4>
            <br>
            <ul class="list-group">
            <?php foreach ($_SESSION['dorc-products'] as $id => $item) : ?>
                <li class="list-group-item" >
                    <h5>
                        <a href="<?php echo get_permalink($id); ?>">
                        <?php if (has_post_thumbnail($id)) : ?>
                            <?php echo get_the_post_thumbnail($id, 'thumbnail', array('class' => 'dorc-thumbnail')); ?>
                        <?php endif; ?>
                        <?php echo $item['title']; ?>
                        </a> (<?php _e('Quantidade: ') ?>
                        <b class="quantity-total"><?php echo $item['quantity_total']; ?></b> )
                        <a href="#" data-prodid="<?php echo $id; ?>" class="dorc-remove-product"><i class="dashicons dashicons-trash"></i></a>
                    </h5>
                    <?php if (isset($item['variations'])) { ?>
                    <ul class="dorc-list-variations-cart" >
                        <?php
                        foreach ($item['variations'] as $key => $variation) {
                            echo dorc_loop_variation_cart_html($variation, $key, $id);
                        }
                        ?>
                    </ul>
                    <?php
                } ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <br>
        <h4 class="description"><?php _e('Preencha os campos abaixo para que possamos entrar em contato.', 'dorc') ?></h4>
        <br>
        <div class="row" >
            <div class="form-group col-sm-6 <?php echo $valid->hasError('client_name') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($name) ? $name : ''; ?>" name="client_name" placeholder="Nome" class="form-control" />
                <?php if ($valid->hasError('client_name')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('client_name'); ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group col-sm-6 <?php echo $valid->hasError('cpf_cnpj') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($cpf_cnpj) ? $cpf_cnpj : ''; ?>" name="cpf_cnpj" placeholder="CPF/CNPJ" class="form-control" />
                <?php if ($valid->hasError('cpf_cnpj')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('cpf_cnpj'); ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group col-sm-6 <?php echo $valid->hasError('email') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($email) ? $email : ''; ?>" name="email" placeholder="Email" class="form-control" />
                <?php if ($valid->hasError('email')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('email'); ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group col-sm-6 <?php echo $valid->hasError('phone') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($phone) ? $phone : ''; ?>" name="phone" placeholder="Telefone" class="form-control" />
                <?php if ($valid->hasError('phone')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('phone'); ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group col-sm-6 <?php echo $valid->hasError('city') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($city) ? $city : ''; ?>" name="city" placeholder="Cidade" class="form-control" />
                <?php if ($valid->hasError('city')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('city'); ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group col-sm-6 <?php echo $valid->hasError('addres') ? ' has-error' : ''; ?>">
                <input type="text" value="<?php echo isset($addres) ? $addres : ''; ?>" name="addres" placeholder="Endereço" class="form-control" />
                <?php if ($valid->hasError('addres')) : ?>
                    <p class="text-danger" ><?php echo $valid->getMessage('addres'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($dorc_google_site_key) : ?>
            <div class="g-recaptcha" data-sitekey="<?php echo $dorc_google_site_key; ?>"></div>
            <?php if ($valid->hasError('g-recaptcha-response')) : ?>
                <p class="text-danger" ><?php echo $valid->getMessage('g-recaptcha-response'); ?></p>
            <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="dorc-form" value="newdorc-form" >
        <input type="submit" name="submit" class="btn btn-primary btn-md pull-right" <?php echo isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products']) ? '' : ' disabled="disabled"' ?> value="ENVIAR PEDIDO DE ORÇAMENTO" />
    </form>
</div>