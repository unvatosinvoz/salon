<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zt230111939@zapotlanejo.tecmm.edu.mx';          //es tu  gmail institucional de preferencia
            $mail->Password   = 'mrvh nkuj ctcr tzsb';  //esta la obtienes en la configuración du gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('zt230111939@zapotlanejo.tecmm.edu.mx', 'AppSalon');
            $mail->addAddress($this->email, $this->nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body    = "<html>
                                <p>Hola <b>{$this->nombre}</b>, confirma tu cuenta en AppSalon.</p>
                                <p>Presiona el siguiente enlace para confirmar tu cuenta:</p>
                                <a href='http://localhost:3000/confirmar-cuenta?token={$this->token}'>Confirmar Cuenta</a> <!-- la ruta es de desarrollo -->
                                <p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>
                              </html>";
            $mail->AltBody = "Hola {$this->nombre}, confirma tu cuenta visitando el siguiente enlace: http://localhost:3000/confirmar-cuenta?token={$this->token}";
            
            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
    public function enviarInstrucciones() {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'josuedavidjy60@gmail.com';
        $mail->Password = '123456';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('josuedavidjy60@gmail.com', 'AppSalon');
        $mail->addAddress($this->email, $this->nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Reestablece tu Password';
        $mail->Body = "
            <html>
            <p>Hola <strong>{$this->nombre}</strong>, has solicitado reestablecer tu password.</p>
            <p>Presiona el siguiente enlace:</p>
            <a href='http://localhost:3000/recuperar?token={$this->token}'>
            Reestablecer Password</a>
            <p>Si tú no solicitaste este cambio, ignora este mensaje.</p>
            </html>";
        $mail->AltBody = "Visita esta URL: http://localhost:3000/recuperar?token={$this->token}";
        $mail->send();

    } catch (Exception $e) {
        echo "El mensaje no pudo enviarse. Error: {$mail->ErrorInfo}";
    }
}
}