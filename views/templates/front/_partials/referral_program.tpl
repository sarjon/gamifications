{*
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 *}

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-primary">{l s='Referral program' d='Modules.Gamifications.Shop'}</h1>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>{l s='Your unique referral link:' d='Modules.Gamifications.Shop'}</h4>
                <input title="{l s='Your unique referral link' d='Modules.Gamifications.Shop'}" class="js-gamifications-referral-url-input form-control" type="text" value="{$referral_url}">
                <a href="#" class="text-muted js-gamifications-referral-url-copy">{l s='Copy to clipboard.' d='Modules.Gamifications.Shop'} </a>
            </div>

            <div class="col-md-6">
                {if $referral_reward_name}
                    <h4>{l s='For every invited friend you get' d='Modules.Gamifications.Shop'}</h4>
                    <h1 class="display-3 text-primary">{$referral_reward_name}</h1>
                {/if}

                {if $new_customer_reward_name}
                    <h4>{l s='Your friend gets' d='Modules.Gamifications.Shop'}</h4>
                    <h1 class="display-3 text-primary">{$new_customer_reward_name}</h1>
                {/if}

                <hr>
                <h1 class="display-3 text-primary">You have already invited 2 of your friends!</h1>
            </div>
        </div>
    </div>
</div>