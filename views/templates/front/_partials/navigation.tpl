{*
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 *}

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-inline">
            <li class="nav-item">
                <a class="nav-link {if $controller == 'loyality'}active{/if}" href="{url entity='module' name='gamifications' controller='loyality'}">
                    {l s='Overview' d='Modules.Gamifications.Shop'}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {if $controller == 'exchangepoints'}active{/if}" href="{url entity='module' name='gamifications' controller='exchangepoints'}">
                    {l s='Exchange points' d='Modules.Gamifications.Shop'}
                </a>
            </li>
        </ul>
    </div>
</div>
