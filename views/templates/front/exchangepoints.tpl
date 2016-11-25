{extends file='page.tpl'}

{block name='page_title'}
    {l s='Exchange points' d='Shop.Theme.CustomerAccount'}
{/block}

{block name="page_content"}

    {include file='module:gamifications/views/templates/front/_partials/navigation.tpl'}

    <div class="row">
        <div class="col-md-12">
            <hr>
        </div>
    </div>

    <div class="row">

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
                                <p>{$reward.description}</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <form method="post">
                                    <button type="submit"
                                            class="btn btn-primary pull-xs-right"
                                            name="exchange_points"
                                            {if $buttonStatus == 'disabled'}disabled{/if}
                                    >
                                        {l s='Exchange' d='Modules.Gamifications.Shop'}
                                        {$reward.points}
                                        {l s='points' d='Modules.Gamifications.Shop'}!
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
                                <p>{$reward.description}</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <form method="post">
                                    <button type="submit"
                                            class="btn btn-primary pull-xs-right"
                                            name="exchange_points"
                                            {if $buttonStatus == 'disabled'}disabled{/if}
                                    >
                                        {l s='Exchange' d='Modules.Gamifications.Shop'}
                                        {$reward.points}
                                        {l s='points' d='Modules.Gamifications.Shop'}!
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

    </div>

{/block}
