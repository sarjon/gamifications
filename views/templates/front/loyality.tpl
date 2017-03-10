{*
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
                    <h3>{l s='My current points' mod='gamifications'}</h3>
                    <h3 class="display-1 text-primary">{$gamifications_customer.total_points} {l s='pts' mod='gamifications'}</h3>
                </div>


                <div class="col-md-3">
                    <h3>{l s='Total points spent' mod='gamifications'}</h3>
                    <h3 class="display-1 text-primary">{$gamifications_customer.spent_points} {l s='pts' mod='gamifications'}</h3>
                </div>
                {if isset($next_reward) && $next_reward}
                    {if $point_exchange.points <= $gamifications_customer.total_points}
                        {assign var="progressColor" value="success"}
                    {elseif $point_exchange.points * 0.2 > $gamifications_customer.total_points}
                        {assign var="progressColor" value="warning"}
                    {else}
                        {assign var="progressColor" value="primary"}
                    {/if}

                    <div class="col-md-6">
                        <h3>{l s='Next available reward' mod='gamifications'}</h3>
                        <div class="card card-outline-primary">
                            <div class=" card-block">
                                <h3 class="card-title">{$next_reward.name}</h3>
                                {if GamificationsReward::REWARD_TYPE_GIFT == $next_reward.reward_type}
                                    <img src="{$next_reward.image_link}" alt="{$next_reward.name}">
                                {/if}
                                <p>{$next_reward.description}</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$point_exchange.points}
                                    {l s='points' mod='gamifications'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$point_exchange.points}"></progress>
                                <a class="pull-xs-right" href="{url entity='module' name='gamifications' controller='exchangepoints'}">
                                    {l s='See all rewards' mod='gamifications'}
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                {/if}
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
