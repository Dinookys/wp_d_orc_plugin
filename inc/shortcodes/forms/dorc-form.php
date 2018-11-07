<?php
/*
    @package portal-chapada
    @subpackage plugin form product
*/
?>
<div class="">        

    <?php if(isset($postID) && is_numeric($postID)) : ?>
    <div class="alert alert-success alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        Orçamento enviado com sucesso!
    </div>    
    <?php else: ?>
        <?php echo isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products']) || $no_products
            ? '' 
            : '<p class="alert alert-info">Você deve adicionar alguns produtos antes de enviar o pedido de orçamento.</p>' 
        ?>
    <?php endif; ?>

    <?php if($valid->isValidPost() == false) : ?>
    <div class="alert alert-warning alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        O todos o campos devem ser preenchidos corretamentes
    </div>    
    <?php endif; ?>

    <form id="dorcorc" action="<?php echo $current_url; ?>" method="POST" class="clearfix no-border-radius" >
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div>            
                    <div class="description">Preencha os campos abaixo para que possamos entrar em contato.</div> <br>
                </div>
                <div class="form-group <?php echo $valid->hasError('client_name') ? ' has-error' : '';  ?>">
                    <input type="text" value="<?php echo isset($name) ? $name : ''; ?>" name="client_name" placeholder="Nome" class="form-control" />            
                    <?php if($valid->hasError('client_name')): ?>
                        <p class="text-danger" ><?php echo $valid->getMessage('client_name'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo $valid->hasError('email') ? ' has-error' : '';  ?>">
                    <input type="text" value="<?php echo isset($email) ? $email : ''; ?>" name="email" placeholder="Email" class="form-control" />            
                    <?php if($valid->hasError('email')): ?>
                        <p class="text-danger" ><?php echo $valid->getMessage('email'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo $valid->hasError('phone') ? ' has-error' : '';  ?>">
                    <input type="text" value="<?php echo isset($phone) ? $phone : ''; ?>" name="phone" placeholder="Telefone" class="form-control" />            
                    <?php if($valid->hasError('phone')): ?>
                        <p class="text-danger" ><?php echo $valid->getMessage('phone'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo $valid->hasError('addres') ? ' has-error' : '';  ?>">
                    <input type="text" value="<?php echo isset($addres) ? $addres : ''; ?>" name="addres" placeholder="Endereço" class="form-control" />            
                    <?php if($valid->hasError('addres')): ?>
                        <p class="text-danger" ><?php echo $valid->getMessage('addres'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 dorc-product-action">
                <?php if(isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products'])) : ?>
                    <h4>Produtos adicionados</h4>
                    <ul class="list-group">
                    <?php foreach($_SESSION['dorc-products'] as $key => $item) : ?>
                        <li class="list-group-item" >
                            <p>
                                <?php echo $item['title']; ?>                                 
                            </p>
                            <div class="actions clearfix">            
                                <p class="message text-primary" style="display: none;" ></p>
                                <input type="number" name="quant" min="1" class="form-control" value="<?php echo $item['quant']; ?>" >
                                <br>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-default minus">-</button>
                                    <button class="btn btn-default plus">+</button>                                    
                                    <button class="btn btn-danger remove"><?php echo __('Remove'); ?></button>
                                </div>
                                <input type="hidden" name="title" value="<?php echo $item['title']; ?>" >
                                <input type="hidden" name="item" value="<?php echo $item['id']; ?>" >        
                            </div>                             
                        </li>
                    <?php endforeach; ?>
                    </ul>                    
                <?php endif; ?>
            </div>
        </div>
        <?php if($dorc_google_site_key): ?>
            <div class="g-recaptcha" data-sitekey="<?php echo $dorc_google_site_key; ?>"></div>
            <?php if($valid->hasError('g-recaptcha-response')): ?>
                <p class="text-danger" ><?php echo $valid->getMessage('g-recaptcha-response'); ?></p>
            <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="dorc-form" value="newdorc-form" >        
        <input type="submit" name="submit" class="btn btn-primary btn-md pull-right" <?php echo isset($_SESSION['dorc-products']) && !empty($_SESSION['dorc-products'])  ? '' : ' disabled="disabled"' ?> value="ENVIAR PEDIDO DE ORÇAMENTO" />
    </form>    
</div>