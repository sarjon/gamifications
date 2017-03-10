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
    {l s='Exchange points' mod='gamifications'}
{/block}

{block name="page_content"}
    <div class="row">

        {if isset($point_exchange_rewards) && !empty($point_exchange_rewards)}
        <div class="col-md-6">
            <div class="row">
                {foreach from=$point_exchange_rewards key=key item=reward}
                    {if $key mod 2 != 0}
                        {continue}
                    {/if}

                    {if $reward.points <= $gamifications_customer.total_points}
                        {assign var="progressColor" value="success"}
                    {elseif $reward.points * 0.2 > $gamifications_customer.total_points}
                        {assign var="progressColor" value="warning"}
                    {else}
                        {assign var="progressColor" value="primary"}
                    {/if}

                    {if $reward.points <= $gamifications_customer.total_points}
                        {assign var="buttonStatus" value="enabled"}
                    {else}
                        {assign var="buttonStatus" value="disabled"}
                    {/if}

                    <div class="col-md-12">
                        <div class="card card-outline-primary">
                            <div class=" card-block">
                                <h3 class="card-title">{$reward.name}</h3>
                                {if GamificationsReward::REWARD_TYPE_GIFT == $reward.reward_type}
                                    <img src="{$reward.image_link}" alt="{$reward.name}">
                                {/if}
                                <p>{$reward.description}</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' mod='gamifications'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <form method="post">
                                    <button type="submit"
                                            class="btn btn-primary pull-xs-right"
                                            name="exchange_points"
                                            {if $buttonStatus == 'disabled'}disabled{/if}
                                    >
                                        {l s='Exchange' mod='gamifications'}
                                        {$reward.points}
                                        {l s='points' mod='gamifications'}!
                                    </button>
                                    <input type="hidden" name="csrf_token" value="{$csrf_token}">
                                    <input type="hidden" name="id_point_exchange_reward" value="{$reward.id_gamifications_point_exchange}">
                                </form>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                {foreach from=$point_exchange_rewards key=key item=reward}
                    {if $key mod 2 == 0}
                        {continue}
                    {/if}

                    {if $reward.points <= $gamifications_customer.total_points}
                        {assign var="progressColor" value="success"}
                    {elseif $reward.points * 0.2 > $gamifications_customer.total_points}
                        {assign var="progressColor" value="warning"}
                    {else}
                        {assign var="progressColor" value="primary"}
                    {/if}

                    {if $reward.points <= $gamifications_customer.total_points}
                        {assign var="buttonStatus" value="enabled"}
                    {else}
                        {assign var="buttonStatus" value="disabled"}
                    {/if}

                    <div class="col-md-12">
                        <div class="card card-outline-primary">
                            <div class=" card-block">
                                <h3 class="card-title">{$reward.name}</h3>
                                {if GamificationsReward::REWARD_TYPE_GIFT == $reward.reward_type}
                                    <img src="{$reward.image_link}" alt="{$reward.name}">
                                {/if}
                                <p>{$reward.description}</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' mod='gamifications'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <form method="post">
                                    <button type="submit"
                                            class="btn btn-primary pull-xs-right"
                                            name="exchange_points"
                                            {if $buttonStatus == 'disabled'}disabled{/if}
                                    >
                                        {l s='Exchange' mod='gamifications'}
                                        {$reward.points}
                                        {l s='points' mod='gamifications'}!
                                    </button>
                                    <input type="hidden" name="csrf_token" value="{$csrf_token}">
                                    <input type="hidden" name="id_point_exchange_reward" value="{$reward.id_gamifications_point_exchange}">
                                </form>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
        {else}
            <article class="alert alert-info" role="alert" data-alert="info">
                <ul>
                    <li>{l s='Theres no rewards at the moment, please check back soon!' mod='gamifications'}</li>
                </ul>
            </article>
        {/if}

    </div>

{/block}
