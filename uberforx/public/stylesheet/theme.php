<?php
header("Content-type: text/css; charset: UTF-8");
$theme="#660066";
$primary="#660036";
$secondary="#660046";
$hover="#660056";
?>
.btn-default {
  color: #ffffff;
  background-color: <?php echo $theme;?>;
}
.btn-success {
  color: #ffffff;
  background-color: <?php echo $theme;?>;
  border-color: <?php echo $theme;?>;
}
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active,
.open .dropdown-toggle.btn-success {
  color: #ffffff;
  background-color: <?php echo $theme;?>;
  border-color: <?php echo $theme;?>;

}


.btn-success.disabled,
.btn-success[disabled],
fieldset[disabled] .btn-success,
.btn-success.disabled:hover,
.btn-success[disabled]:hover,
fieldset[disabled] .btn-success:hover,
.btn-success.disabled:focus,
.btn-success[disabled]:focus,
fieldset[disabled] .btn-success:focus,
.btn-success.disabled:active,
.btn-success[disabled]:active,
fieldset[disabled] .btn-success:active,
.btn-success.disabled.active,
.btn-success[disabled].active,
fieldset[disabled] .btn-success.active {

  background-color: <?php echo $theme;?>;
  border-color: <?php echo $theme;?>;
}
.btn-success .badge {
  color: <?php echo $theme;?>;
  background-color: #ffffff;
}
.btn-info {
  color: #ffffff;
  background-color: <?php echo $theme;?>;
  border-color: <?php echo $theme;?>;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  color: #000;
  background-color: #FFFF;
  border-color: <?php echo $theme;?>;
}
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  background-image: none;
}
.btn-info.disabled,
.btn-info[disabled],
fieldset[disabled] .btn-info,
.btn-info.disabled:hover,
.btn-info[disabled]:hover,
fieldset[disabled] .btn-info:hover,
.btn-info.disabled:focus,
.btn-info[disabled]:focus,
fieldset[disabled] .btn-info:focus,
.btn-info.disabled:active,
.btn-info[disabled]:active,
fieldset[disabled] .btn-info:active,
.btn-info.disabled.active,
.btn-info[disabled].active,
fieldset[disabled] .btn-info.active {
  background-color: <?php echo $theme;?>;
  border-color: <?php echo $theme;?>;
}
.btn-info .badge {
  color: <?php echo $theme;?>;
  background-color: #029acf;
  border-color: #029acf;
}
.btn-success,
.btn-success:hover {
  background-image: -webkit-linear-gradient(<?php echo $theme;?> <?php echo $theme;?> 6%, <?php echo $theme;?>);
  background-image: linear-gradient(<?php echo $theme;?>, <?php echo $theme;?> 6%, <?php echo $theme;?>);
  background-repeat: no-repeat;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $theme;?>', endColorstr='<?php echo $theme;?>', GradientType=0);
  filter: none;
  border: 1px solid <?php echo $theme;?>;
}
.btn-info,
.btn-info:hover {
  background-image: -webkit-linear-gradient(<?php echo $theme;?>, <?php echo $theme;?> 6%, <?php echo $theme;?>);
  background-image: linear-gradient(<?php echo $theme;?>, <?php echo $theme;?> 6%, <?php echo $theme;?>);
  background-repeat: no-repeat;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $theme;?>', endColorstr='<?php echo $theme;?>', GradientType=0);
  filter: none;
  border: 1px solid <?php echo $theme;?>;
}
.logo h3{
    margin: 0px;
    color: <?php echo $theme;?>;
}

.second-nav{
    background: <?php echo $theme;?>;
}
.login_back{background-color: <?php echo $theme;?>;}
.no_radious:hover{background-image: -webkit-linear-gradient(<?php echo $theme;?>, <?php echo $theme;?> 6%, <?php echo $theme;?>);background-image: linear-gradient(#5d4dd1, #5d4dd1 6%, #5d4dd1);background-repeat: no-repeat;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5d4dd1', endColorstr='#5d4dd1', GradientType=0);filter: none;border: 1px solid #5d4dd1;}

.nav-pills li:nth-child(1) a{
    background: <?php echo $primary; ?>;
}

.nav-pills li:nth-child(2) a{
    background: <?php echo $secondary; ?>;
}

.nav-pills li:nth-child(3) a{
    background: <?php echo $primary; ?>;
}

.nav-pills li:nth-child(4) a{
    background: <?php echo $secondary; ?>;
}

.nav-pills li:nth-child(5) a{
    background: <?php echo $primary; ?>;
}

.nav-pills li:nth-child(6) a{
    background: <?php echo $secondary; ?>;
}

.nav-pills li:nth-child(7) a{
    background: <?php echo $primary; ?>;
}

.nav-pills li:nth-child(8) a{
    background: <?php echo $secondary; ?>;
}

.nav-pills li:nth-child(9) a{
    background: <?php echo $primary; ?>;
}

.nav-pills li:nth-child(10) a{
    background: <?php echo $secondary; ?>;
}

.nav-pills li a:hover{
    background: <?php echo $hover;?>;
}