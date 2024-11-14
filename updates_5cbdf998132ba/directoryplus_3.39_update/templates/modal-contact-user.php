<!-- modal contact user -->
<div id="contact-user-modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?php
					if($is_tpl_listing) {
						?>
						<?= $txt_contact_business ?>
						<?php
					}

					if(in_array($route[0], array('profile', 'favorites', 'reviews'))) {
						if(!empty($profile_id)) {
							?>
							<?= $txt_contact_user ?>
							<?php
						}
					}
					?>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				if(!empty($phone)) {
					?>
					<div class="mb-3">
						<div><strong><?= $txt_phone ?></strong></div>
						<a href="tel:<?= !empty($country_code) ? '+' . $country_code : '' ?><?= preg_replace("/[^0-9]/", "", $area_code) ?><?= preg_replace("/[^0-9]/", "", $phone) ?>" class="btn btn-light">
							<strong><i class="fas fa-phone"></i>
								<?php
								if($cfg_show_country_calling_code && !empty($country_code)) {
									?>
									+<?= $country_code ?>
									<?php
								}
								?>
								<?= !empty($area_code) ? '(' . $area_code . ')' : '' ?>
								<?= $phone ?>
							</strong>
						</a>
					</div>
					<?php
				}
				?>

				<?php
				if(!empty($wa_country_code) && !empty($wa_phone)) {
					?>
					<div class="mb-3">
						<div><strong><?= $txt_click_to_chat ?></strong></div>
						<a href="https://wa.me/<?= $wa_country_code . $wa_area_code . $wa_phone ?>" class="btn" style="background-color:#25d366;color:white" target="_blank"><i class="fab fa-whatsapp"></i> Whatsapp</a>
					</div>
					<?php
				}
				?>

				<div><strong><?= $txt_send_email ?></strong></div>
				<div id="contact-user-result"></div>
				<form id="contact-user-form" method="post">
					<?php
					if($is_tpl_listing) {
						?>
						<input type="hidden" name="place_id" value="<?= $place_id ?>">
						<input type="hidden" name="from_page" value="listing">
						<input type="hidden" name="listing_url" value="<?= $canonical ?>">
						<?php
					}

					if(in_array($route[0], array('profile', 'favorites', 'reviews'))) {
						if(!empty($profile_id)) {
							?>
							<input type="hidden" id="recipient_id" name="recipient_id" value="<?= $profile_id ?>">
							<input type="hidden" id="from_page" name="from_page" value="profile">
							<?php
						}
					}
					?>

					<div class="form-group">
						<input type="text" id="sender_name" class="form-control" name="sender_name" placeholder="<?= $txt_name ?>" required>
					</div>

					<div class="form-group">
						<input type="email" id="sender_email" class="form-control" name="sender_email" placeholder="<?= $txt_email ?>" required>
					</div>

					<div class="form-group">
						<textarea id="sender_msg" class="form-control" name="sender_msg" rows="5" placeholder="<?= $txt_message ?>" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="contact-user-close" class="btn btn-light" data-dismiss="modal"><?= $txt_close ?></button>
				<button type="button" id="contact-user-cancel" class="btn btn-secondary" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="submit" id="contact-user-submit" class="btn btn-primary"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>