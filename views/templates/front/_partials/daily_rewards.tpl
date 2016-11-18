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
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                {if $can_play_daily_reward}}
                                    <form method="post">
                                        <button type="submit" name="get_daily_reward" class="btn btn-primary">
                                            {l s='Get my Daily Reward!' d='Modules.Gamifications.Shop'}
                                        </button>
                                    </form>
                                {else}
                                    <button type="submit" name="get_daily_reward" class="btn btn-primary disabled">
                                        {l s='Your next reward is coming' d='Modules.Gamifications.Shop'}
                                    </button>
                                    <p class="card-text">
                                        {l s='Next Daily Reward available at' d='Modules.Gamifications.Shop'}:
                                        <em>{$next_daily_reward_availabe_at}</em>
                                    </p>
                                {/if}
                            </div>

                            <div class="col-md-6">
                                <h4 class="card-title">Total points earned from Daily Rewards</h4>
                                <h3 class="display-1">247 pts</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>