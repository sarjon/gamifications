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
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <button type="submit"
                                        class="btn btn-primary pull-xs-right"
                                        {if $buttonStatus == 'disabled'}disabled{/if}
                                >
                                    {l s='Exchange' d='Modules.Gamifications.Shop'}
                                    {$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}!
                                </button>
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
                        {assign var="progressColor" value="info"}
                    {/if}

                    <div class="col-md-12">
                        <div class="card card-outline-primary">
                            <div class=" card-block">
                                <h3 class="card-title">{$reward.name}</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                                <div class="text-xs-center text-muted" id="example-caption-1">
                                    {$gamifications_customer.total_points}/{$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}
                                </div>
                                <progress class="progress progress-{$progressColor}" value="{$gamifications_customer.total_points}" max="{$reward.points}"></progress>
                                <button class="btn btn-primary pull-xs-right disabled">
                                    {l s='Exchange' d='Modules.Gamifications.Shop'}
                                    {$reward.points}
                                    {l s='points' d='Modules.Gamifications.Shop'}!
                                </button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>

        {*<div class="col-md-6">*}
            {*<div class="row">*}
                {*<div class="col-md-12">*}
                    {*<div class="card card-outline-primary">*}
                        {*<div class=" card-block">*}
                            {*<h3 class="card-title">25$ Discount to cart over 250$!</h3>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>*}
                            {*<div class="text-xs-center text-muted" id="example-caption-1">98/150 points</div>*}
                            {*<progress class="progress progress-info" value="98" max="150"></progress>*}
                            {*<button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>*}
                            {*<div class="clearfix"></div>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}
                {*<div class="col-md-12">*}
                    {*<div class="card card-outline-primary">*}
                        {*<div class=" card-block">*}
                            {*<h3 class="card-title">20% Discount to cart over 500$!</h3>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>*}
                            {*<div class="text-xs-center text-muted" id="example-caption-1">150/150 points</div>*}
                            {*<progress class="progress progress-success" value="150" max="150"></progress>*}
                            {*<button class="btn btn-primary pull-xs-right">Exchange 150 points!</button>*}
                            {*<div class="clearfix"></div>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}
            {*</div>*}

        {*</div>*}

        {*<div class="col-md-6">*}
            {*<div class="row">*}
                {*<div class="col-md-12">*}
                    {*<div class="card card-outline-primary">*}
                        {*<div class=" card-block">*}
                            {*<h3 class="card-title">Free shipping</h3>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Morbi mi justo, venenatis nec dolor quis, accumsan fringilla sem.</p>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Morbi mi justo, venenatis nec dolor quis, accumsan fringilla sem.</p>*}
                            {*<div class="text-xs-center text-muted" id="example-caption-1">10/150 points</div>*}
                            {*<progress class="progress progress-warning" value="10" max="150"></progress>*}
                            {*<button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>*}
                            {*<div class="clearfix"></div>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}
                {*<div class="col-md-12">*}
                    {*<div class="card card-outline-primary">*}
                        {*<div class=" card-block">*}
                            {*<h3 class="card-title">10% Discount to cart over 50$!</h3>*}
                            {*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>*}
                            {*<div class="text-xs-center text-muted" id="example-caption-1">98/150 points</div>*}
                            {*<progress class="progress progress-info" value="98" max="150"></progress>*}
                            {*<button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>*}
                            {*<div class="clearfix"></div>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}
            {*</div>*}

        {*</div>*}
    </div>

{/block}