{*
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *}

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-primary">{l s='Referral program' mod='gamifications'}</h1>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>{l s='Your unique referral link:' mod='gamifications'}</h4>
                <input title="{l s='Your unique referral link' mod='gamifications'}" class="js-gamifications-referral-url-input form-control" type="text" value="{$referral_url}">
                <a href="#" class="text-muted js-gamifications-referral-url-copy">{l s='Copy to clipboard.' mod='gamifications'} </a>
            </div>

            <div class="col-md-6">
                {if $referral_reward_name}
                    <h4>{l s='For every invited friend you get' mod='gamifications'}</h4>
                    <h1 class="display-3 text-primary">{$referral_reward_name}</h1>
                {/if}

                {if $new_customer_reward_name}
                    <h4>{l s='Your friend gets' mod='gamifications'}</h4>
                    <h1 class="display-3 text-primary">{$new_customer_reward_name}</h1>
                {/if}

                <hr>
                {if $invited_customers_count}
                    <h1 class="display-3 text-primary">{l s='You have already invited %s of your friends!' sprintf=[$invited_customers_count] mod='gamifications'}</h1>
                {else}
                    <h1 class="display-3 text-primary">{l s='You have not invited any of your friends yet' mod='gamifications'}</h1>
                {/if}
            </div>
        </div>
    </div>
</div>