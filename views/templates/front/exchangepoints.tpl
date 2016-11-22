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
                <div class="col-md-12">
                    <div class="card card-outline-primary">
                        <div class=" card-block">
                            <h3 class="card-title">25$ Discount to cart over 250$!</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                            <div class="text-xs-center text-muted" id="example-caption-1">98/150 points</div>
                            <progress class="progress progress-info" value="98" max="150"></progress>
                            <button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-outline-primary">
                        <div class=" card-block">
                            <h3 class="card-title">20% Discount to cart over 500$!</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                            <div class="text-xs-center text-muted" id="example-caption-1">150/150 points</div>
                            <progress class="progress progress-success" value="150" max="150"></progress>
                            <button class="btn btn-primary pull-xs-right">Exchange 150 points!</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline-primary">
                        <div class=" card-block">
                            <h3 class="card-title">Free shipping</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Morbi mi justo, venenatis nec dolor quis, accumsan fringilla sem.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque. Morbi mi justo, venenatis nec dolor quis, accumsan fringilla sem.</p>
                            <div class="text-xs-center text-muted" id="example-caption-1">10/150 points</div>
                            <progress class="progress progress-warning" value="10" max="150"></progress>
                            <button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-outline-primary">
                        <div class=" card-block">
                            <h3 class="card-title">10% Discount to cart over 50$!</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl eget augue tristique, nec interdum nisl scelerisque.</p>
                            <div class="text-xs-center text-muted" id="example-caption-1">98/150 points</div>
                            <progress class="progress progress-info" value="98" max="150"></progress>
                            <button class="btn btn-primary pull-xs-right disabled">Exchange 150 points!</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

{/block}
