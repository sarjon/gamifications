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
                <h1 class="text-primary">{l s='Daily Rewards' mod='gamifications'}</h1>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        {if $can_play_daily_reward}
                            <h4>{l s='It\'s ready for you!' mod='gamifications'}</h4>
                            <form method="post">
                                <button type="submit" name="get_daily_reward" class="btn btn-primary btn-block">
                                    {l s='Get my Daily Reward!' mod='gamifications'}
                                </button>
                            </form>
                        {else}
                            <h4>{l s='There\'s always another day!' mod='gamifications'}</h4>
                            <button type="submit" name="get_daily_reward" class="btn btn-primary btn-block disabled">
                                {l s='Daily Reward is not available at the moment' mod='gamifications'}
                            </button>
                            <p class="card-text">
                                {l s='Next Daily Reward available' mod='gamifications'}
                                {$next_daily_reward_availabe_at}
                            </p>
                        {/if}
                        <br class="hidden-md-up">
                    </div>

                    <div class="col-md-6">
                        <h4>{l s='How it works?' mod='gamifications'}</h4>
                        <ul class="list-style">
                           <li>{l s='1. Visit our shop daily' mod='gamifications'}</li>
                           <li>{l s='2. Get Your Daily Reward!' mod='gamifications'}</li>
                           <li>{l s='3. Spend Your points to get awesome prizes!' mod='gamifications'}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>