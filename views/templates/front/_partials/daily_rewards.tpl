<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-primary">{l s='Daily Rewards' d='Modules.Gamifications.Shop'}</h1>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        {if $can_play_daily_reward}
                            <h4>{l s='It\'s ready for you!' d='Modules.Gamifications.Shop'}</h4>
                            <form method="post">
                                <button type="submit" name="get_daily_reward" class="btn btn-primary btn-block">
                                    {l s='Get my Daily Reward!' d='Modules.Gamifications.Shop'}
                                </button>
                            </form>
                        {else}
                            <h4>{l s='There\'s always another day!' d='Modules.Gamifications.Shop'}</h4>
                            <button type="submit" name="get_daily_reward" class="btn btn-primary disabled">
                                {l s='Daily Reward is not available yet' d='Modules.Gamifications.Shop'}
                            </button>
                            <p class="card-text">
                                {l s='Next Daily Reward available at' d='Modules.Gamifications.Shop'}
                                <em>{$next_daily_reward_availabe_at}</em>
                            </p>
                        {/if}
                        <br class="hidden-md-up">
                    </div>

                    <div class="col-md-6">
                        <h4>{l s='How it works?' d='Modules.Gamifications.Shop'}</h4>
                        <ul class="list-style">
                           <li>{l s='1. Visit our shop daily' d='Modules.Gamifications.Shop'}</li>
                           <li>{l s='2. Get Your Daily Reward!' d='Modules.Gamifications.Shop'}</li>
                           <li>{l s='3. Spend Your points to get awesome prizes!' d='Modules.Gamifications.Shop'}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>