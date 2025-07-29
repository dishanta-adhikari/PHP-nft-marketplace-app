<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Controllers\NFTController;
use App\Helpers\Flash;

$nftController = new NFTController($con);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 6;

$results = $nftController->getUserNFTs($_SESSION['user_id'], $page, $limit);
$ownedNFTs = $results['nfts'];
$totalPages = $results['totalPages'];

?>


<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/dashboard.css">
<div class="container my-4">
    <?php Flash::render(); ?>

    <h1>Your NFTs</h1>


    <div id="paymentAlert" class="alert alert-success d-none nft-payment-alert" role="alert"></div>

    <div class="row">
        <?php if (empty($ownedNFTs)): ?>
            <div class="col-12">
                <div class="alert alert-warning">You don't own any NFTs yet.</div>
            </div>
        <?php endif; ?>

        <?php foreach ($ownedNFTs as $nft): ?>
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm nft-card">
                    <img src="<?= APP_URL . "/public/uploads/images/" . htmlspecialchars($nft['Photo']) ?>" class="card-img-top nft-card-img" alt="<?= htmlspecialchars($nft['Title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title nft-card-title"><?= htmlspecialchars($nft['Title']) ?></h5>
                        <p class="nft-price-text"><strong>Price:</strong> $<?= number_format($nft['Price'], 2) ?></p>

                        <?php if ($nft['Is_Paid']): ?>
                            <a
                                href="<?= APP_URL . "/public/uploads/images/" . htmlspecialchars($nft['Photo']) ?>"
                                class="btn btn-primary nft-download-btn"
                                download="<?= preg_replace('/\s+/', '_', $nft['Title']) ?>.<?= pathinfo($nft['Photo'], PATHINFO_EXTENSION) ?>">
                                Download
                            </a>

                            <p class="mt-2">
                                <strong>Token:</strong>
                                <code class="text-break"><?= htmlspecialchars($nft['token']) ?></code>
                            </p>
                        <?php else: ?>
                            <button
                                class="btn nft-pay-btn pay-btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#paymentModal"
                                data-title="<?= htmlspecialchars($nft['Title']) ?>"
                                data-price="<?= number_format($nft['Price'], 2) ?>"
                                data-nftid="<?= $nft['NFT_ID'] ?>">
                                Pay to Download
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">NFT Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>NFT:</strong> <span id="nftTitle"></span></p>
                <p><strong>Price:</strong> $<span id="nftPrice"></span></p>
                <hr>
                <p>This is a fake payment modal for demo purposes only.</p>
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" />
                </div>
                <div class="row">
                    <div class="col">
                        <label for="expiry" class="form-label">Expiry</label>
                        <input type="text" class="form-control" id="expiry" placeholder="MM/YY" />
                    </div>
                    <div class="col">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" placeholder="123" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="payNowBtn" type="button" class="btn btn-success">Pay Now</button>
            </div>
        </div>
    </div>
</div>

<script>
    const paymentModal = document.getElementById('paymentModal');
    const payNowBtn = document.getElementById('payNowBtn');
    const paymentAlert = document.getElementById('paymentAlert');

    let currentNFTId = null;

    paymentModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        currentNFTId = button.getAttribute('data-nftid');

        document.getElementById('nftTitle').textContent = button.getAttribute('data-title');
        document.getElementById('nftPrice').textContent = button.getAttribute('data-price');

        document.getElementById('cardNumber').value = '';
        document.getElementById('expiry').value = '';
        document.getElementById('cvv').value = '';
    });

    payNowBtn.addEventListener('click', () => {
        if (!currentNFTId) return;

        const modal = bootstrap.Modal.getInstance(paymentModal);
        modal.hide();

        const formData = new FormData();
        formData.append('nft_id', currentNFTId);

        fetch('<?= APP_URL ?>/payment', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    paymentAlert.textContent = 'Payment successful! Thank you.';
                    paymentAlert.classList.remove('d-none');

                    paymentAlert.scrollIntoView({
                        behavior: 'smooth'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1300);
                } else {
                    alert('Payment failed: ' + data.message);
                }
            })
            .catch(err => {
                alert('Error occurred. Please try again.');
                console.error(err);
            });
    });
</script>