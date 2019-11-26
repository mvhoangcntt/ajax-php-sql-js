<?php 
$this->load->view("partical/header");
echo $layout;
?>

<script type="text/javascript">
	const BASE_URL = '<?php echo base_url() ?>';
</script>

<?php 
$this->load->view("partical/footder");
?>