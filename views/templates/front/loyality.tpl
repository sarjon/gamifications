{*
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 *}

{extends file='page.tpl'}

{block name='page_title'}
    {$front_office_title}
{/block}

{block name="page_content"}
    <div class="row">

        <div class="col-md-12">

            <div class="row">

                <div class="col-md-3">
                    <h3>{l s='My current points' d='Modules.Gamifications.Shop'}</h3>
                    <h3 class="display-1 text-primary">{$gamifications_customer->total_points} {l s='pts' d='Modules.Gamifications.Shop'}</h3>
                </div>


                <div class="col-md-3">
                    <h3>{l s='Total points spent' d='Modules.Gamifications.Shop'}</h3>
                    <h3 class="display-1 text-primary">{$gamifications_customer->spent_points} {l s='pts' d='Modules.Gamifications.Shop'}</h3>
                </div>

                <div class="col-md-6">
                    <h3>{l s='Next available reward' d='Modules.Gamifications.Shop'}</h3>
                    <div class="card card-outline-primary">
                        <div class=" card-block">
                            <h3 class="card-title">10% Discount to cart over 50$!</h3>
                            <div class="text-xs-center text-muted" id="example-caption-1">98/150 points</div>
                            <progress class="progress progress-info" value="98" max="150"></progress>
                            <a class="d-inline pull-xs-right" href="#">Check out all rewards</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {if $is_daily_rewards_enabled}
        {include file='module:gamifications/views/templates/front/_partials/daily_rewards.tpl'}
    {/if}

    {if $is_referral_program_enabled}
        {include file='module:gamifications/views/templates/front/_partials/referral_program.tpl'}
    {/if}

{/block}
