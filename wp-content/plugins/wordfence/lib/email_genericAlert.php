<?php if (!defined('WORDFENCE_VERSION')) { exit; } ?>
This email was sent from your website "<?php echo $blogName; ?>" by the Wordfence plugin at <?php echo $date; ?>

The Wordfence administrative URL for this site is: <?php echo network_admin_url('admin.php?page=Wordfence'); ?>

<?php echo $alertMsg; ?>
<?php if($IPMsg){ echo "\n$IPMsg\n"; } ?>

<?php if(! $isPaid){ ?>
NOTE: You are using the free version of Wordfence. Upgrade today:
<<<<<<< HEAD
 - Advanced features like IP reputation monitoring, country blocking, an advanced comment spam filter and cell phone sign-in give you the best protection available
 - Remote, frequent and scheduled scans
 - Access to Premium Support
 - Discounts of up to 90% for multiyear and multi-license purchases
=======
 - Receive real-time Firewall and Scan engine rule updates for protection as threats emerge
 - Real-time IP Blacklist blocks the most malicious IPs from accessing your site
 - Country blocking
 - Two factor authentication
 - IP reputation monitoring
 - Advanced comment spam filter
 - Schedule scans to run more frequently and at optimal times
 - Access to Premium Support
 - Discounts for multi-license purchases
>>>>>>> 01cd3400df28de7997230e7b4299d723a1154df5

Click here to upgrade to Wordfence Premium:
https://www.wordfence.com/zz1/wordfence-signup/
<?php } ?>

--
To change your alert options for Wordfence, visit:
<?php echo $myOptionsURL; ?>

To see current Wordfence alerts, visit:
<?php echo $myHomeURL; ?>



