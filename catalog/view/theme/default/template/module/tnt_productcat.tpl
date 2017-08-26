<?php if ($position == 'content_top'  or $position == 'content_bottom') { ?>
<?php if($products){ ?>
<div class="box">
  <div class="box-heading"><a href="<?php echo $category_href ;?>"><?php echo $category_name; ?>...</a></div>
  <div class="box-content">
    <div class="box-product slider <?php echo $category_id;?>">
      <ul>
        <?php foreach ($products as $product) { ?>
           <li>
            <div>
              <?php if ($product['thumb']) { ?>
              <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
              <?php } ?>
              <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo substr($product['name'],0,50); ?></a></div>
              <?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                <?php } ?>
              </div>
          <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
              <?php } ?>     
            </div>
          </li>  
      <?php } ?>       
      </ul>
    </div>
  </div>
</div>
<?php } ?>

 <?php } else if ($position == 'column_left' or $position == 'column_right') { ?>        
<?php if($products){ ?> 
   <div class="box-left">
  <div class="box-heading"><?php echo $category_name; ?></div>
  <div class="box-content">
    <div class="box-product-left">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
		<div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>                                   
     <?php } ?>  
   <?php } ?>
