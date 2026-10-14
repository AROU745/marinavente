{{-- Modales devis + paiement (Communication Digitale & Conception d'application) --}}
<div class="dig-modal" id="digDevisModal" hidden>
    <div class="dig-modal-backdrop" data-dig-close="devis"></div>
    <div class="dig-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="digDevisTitle">
        <button type="button" class="dig-modal-close" data-dig-close="devis" aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>
        <h2 id="digDevisTitle">Demander un devis</h2>
        <p class="dig-modal-sub">Décrivez votre besoin. Le service / pack est prérempli automatiquement.</p>
        <form id="digDevisForm" autocomplete="off" onsubmit="return false;">
            <div class="dig-field">
                <label for="digDevisNom">Votre nom *</label>
                <input type="text" id="digDevisNom" name="nom" required placeholder="Entrez votre nom complet">
            </div>
            <div class="dig-field">
                <label for="digDevisEmail">Votre e-mail</label>
                <input type="email" id="digDevisEmail" name="email" placeholder="exemple@email.com">
            </div>
            <div class="dig-field">
                <label for="digDevisPhone">Votre téléphone</label>
                <input type="tel" id="digDevisPhone" name="telephone" placeholder="+228 XX XX XX XX">
            </div>
            <div class="dig-field">
                <label for="digDevisService">Service / Pack</label>
                <input type="text" id="digDevisService" name="service" readonly>
            </div>
            <div class="dig-field">
                <label for="digDevisMsg">Votre devis / message *</label>
                <textarea id="digDevisMsg" name="message" rows="5" required placeholder="Décrivez votre projet, vos objectifs, votre budget approximatif..."></textarea>
            </div>
            <button type="submit" class="btn dig-btn-solid dig-btn-full" id="digDevisSubmit">
                <i class="fas fa-paper-plane"></i> Envoyer la demande
            </button>
            <p class="dig-modal-feedback" id="digDevisFeedback" hidden></p>
        </form>
    </div>
</div>

<div class="dig-modal" id="digPayModal" hidden>
    <div class="dig-modal-backdrop" data-dig-close="pay"></div>
    <div class="dig-modal-dialog dig-modal-dialog--pay" role="dialog" aria-modal="true" aria-labelledby="digPayTitle">
        <button type="button" class="dig-modal-close" data-dig-close="pay" aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>
        <h2 id="digPayTitle">Payer maintenant</h2>
        <p class="dig-modal-course" id="digPayService">—</p>
        <form id="digPayForm" autocomplete="off" onsubmit="return false;">
            <div id="digPayFields">
                <h3 class="dig-pay-heading">Paiement immédiat</h3>
                <p class="dig-modal-sub">Renseignez vos informations et choisissez votre mode de paiement.</p>

                <div class="dig-field">
                    <label for="digPayClientNom">Votre nom *</label>
                    <input type="text" id="digPayClientNom" placeholder="Nom et prénom">
                </div>
                <div class="dig-field">
                    <label for="digPayEmail">Votre e-mail *</label>
                    <input type="email" id="digPayEmail" placeholder="exemple@email.com">
                </div>
                <div class="dig-field">
                    <label for="digPayAppDesc">Décrivez votre application *</label>
                    <textarea id="digPayAppDesc" rows="3" placeholder="Fonctionnalités, type d'application (web, mobile, desktop), objectifs..."></textarea>
                </div>

                <p class="dig-pay-method-label">Mode de paiement *</p>
                <div class="dig-pay-methods" role="radiogroup" aria-label="Mode de paiement">
                    <button type="button" class="dig-pay-method is-active" data-method="mobile" aria-pressed="true">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Mobile Money</span>
                    </button>
                    <button type="button" class="dig-pay-method" data-method="visa" aria-pressed="false">
                        <i class="fas fa-credit-card"></i>
                        <span>Visa</span>
                    </button>
                    <button type="button" class="dig-pay-method" data-method="crypto" aria-pressed="false">
                        <i class="fab fa-bitcoin"></i>
                        <span>Crypto</span>
                    </button>
                </div>
                <input type="hidden" id="digPayMethod" value="mobile">

                {{-- Mobile Money --}}
                <div class="dig-pay-panel" id="digPayPanelMobile" data-panel="mobile">
                    <div class="dig-field">
                        <label for="digPayMmOperator">Opérateur *</label>
                        <select id="digPayMmOperator">
                            <option value="">Choisir…</option>
                            <option value="tmoney">T-Money</option>
                            <option value="flooz">Flooz</option>
                            <option value="moov">Moov Money</option>
                        </select>
                    </div>
                    <div class="dig-field">
                        <label for="digPayMmPhone">Numéro Mobile Money *</label>
                        <input type="tel" id="digPayMmPhone" inputmode="tel" placeholder="+228 XX XX XX XX">
                    </div>
                </div>

                {{-- Visa --}}
                <div class="dig-pay-panel" id="digPayPanelVisa" data-panel="visa" hidden>
                    <div class="dig-field">
                        <label for="digPayNom">Nom sur la carte *</label>
                        <input type="text" id="digPayNom" placeholder="Nom complet comme sur la carte">
                    </div>
                    <div class="dig-field">
                        <label for="digPayCard">Numéro de carte *</label>
                        <div class="dig-input-icon">
                            <input type="text" id="digPayCard" inputmode="numeric" maxlength="19" placeholder="XXXX XXXX XXXX XXXX">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                    <div class="dig-form-row">
                        <div class="dig-field">
                            <label for="digPayExp">Expiration *</label>
                            <input type="text" id="digPayExp" inputmode="numeric" maxlength="5" placeholder="MM/AA">
                        </div>
                        <div class="dig-field">
                            <label for="digPayCvc">CVC *</label>
                            <input type="text" id="digPayCvc" inputmode="numeric" maxlength="4" placeholder="123">
                        </div>
                    </div>
                </div>

                {{-- Crypto --}}
                <div class="dig-pay-panel" id="digPayPanelCrypto" data-panel="crypto" hidden>
                    <div class="dig-field">
                        <label for="digPayCryptoType">Cryptomonnaie *</label>
                        <select id="digPayCryptoType">
                            <option value="">Choisir…</option>
                            <option value="usdt">USDT (Tether)</option>
                            <option value="btc">Bitcoin (BTC)</option>
                            <option value="eth">Ethereum (ETH)</option>
                        </select>
                    </div>
                    <div class="dig-field">
                        <label for="digPayCryptoWallet">Adresse de portefeuille *</label>
                        <input type="text" id="digPayCryptoWallet" placeholder="Votre adresse wallet">
                    </div>
                </div>

                <div class="dig-amount-box">
                    Montant à payer : <strong id="digPayAmount">—</strong>
                </div>
                <button type="button" class="btn dig-btn-solid dig-btn-full" id="digPaySubmit">
                    <i class="fas fa-lock"></i> Payer maintenant
                </button>
            </div>
            <div id="digPaySuccess" hidden>
                <div class="dig-success">
                    <div class="dig-success-icon"><i class="fas fa-check"></i></div>
                    <h3>Paiement réussi !</h3>
                    <p id="digPaySuccessMsg"></p>
                </div>
            </div>
        </form>
    </div>
</div>
