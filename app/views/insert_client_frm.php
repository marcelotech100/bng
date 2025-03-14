<div class="container-fluid mt-5 mb-5">
    <div class="row justify-content-center pb-5">
        <div class="col-lg-8 col-md-10">
            <div class="card p-4">

                <div class="row justify-content-center">
                    <div class="col-10">

                        <h4><strong>Inserir novo cliente</strong></h4>

                        <hr>

                        <form action="?ct=agent&mt=new_client_submit" method="post" >

                            <div class="mb-3">
                                <label for="text_name" class="form-label">Nome</label>
                                <input type="text" name="text_name" id="text_name" value="<?php echo isset($sent_data) ? $sent_data['text_name'] : '' ?>" class="form-control" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-12">
                                    <div>Sexo</div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radio_gender" id="radio_m" value="m" <?php echo (!isset($sent_data['radio_gender']) || $sent_data['radio_gender'] == 'm') ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="radio_m">Masculino</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radio_gender" id="radio_f" value="f" <?php echo (isset($sent_data['radio_gender']) && $sent_data['radio_gender'] == 'f') ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="radio_f">Feminino</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="text_birthdate" class="form-label">Data de nascimento</label>
                                    <input type="text" class="form-control" name="text_birthdate" id="text_birthdate" value="<?php echo isset($sent_data) ? $sent_data['text_birthdate'] : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-12">
                                    <label for="text_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="text_email" id="text_email" value="<?php echo isset($sent_data) ? $sent_data['text_email'] : '' ?>" required>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="text_phone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="text_phone" id="text_phone"
                                        value="<?php echo isset($sent_data) ? $sent_data['text_phone'] : '' ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="text_interests" class="form-label">Interesses<span class="ms-4"><small>(Palavras separadas por virgulas)</small></span></label>
                                <input type="text" class="form-control" name="text_interests" value="<?php echo isset($sent_data) ? $sent_data['text_interests'] : '' ?>" id="text_interests">
                            </div>

                            <div class="mb-3 text-center">
                                <a href="?ct=agent&mt=my_clients" class="btn btn-secondary"><i class="fa-solid fa-xmark me-2"></i>Cancelar</a>
                                <button type="submit" class="btn btn-secondary"><i class="fa-regular fa-floppy-disk me-2"></i>Guardar</button>
                            </div>

                            <?php if (isset($validation_errors)): ?>
                                <div class="alert alert-danger p-2">
                                    <ul>
                                        <?php foreach ($validation_errors as $error): ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($server_error)): ?>
                                <div class="alert alert-danger p-2 text-center">
                                    <?= $server_error ?>
                                </div>
                            <?php endif; ?>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    flatpickr("#text_birthdate", {
        locale: {
            firstDayOfWeek: 0,
            weekdays: {
                shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado']
            },
            months: {
                shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
            }
        },
        dateFormat: "d-m-Y"
    })
</script>