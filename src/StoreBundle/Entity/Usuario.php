<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\UsuarioRepository")
 * 
 * @UniqueEntity(fields="username", message="username.repetido")
 */
class Usuario implements AdvancedUserInterface 
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUsuario", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      maxMessage = "username.largo"
     * )
     * @ORM\Column(name="username", type="string",length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 15,
     *      minMessage = "contraseña.corta",
     *      maxMessage = "contraseña.larga"                  
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="habilitado", type="boolean")
     */
    private $habilitado;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"ROLE_ADMIN","ROLE_GESTION", "ROLE_CONSULTA"}, message= "rol.invalido")
     * @ORM\Column(name="rol", type="string", length=255)
     */
    private $rol;

    /**
     * @var string
     *
     * @Assert\Email(message = "email.invalido")
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
    * @var boolean
    * @ORM\Column(name="borrado", type="boolean")
    *
    */
    private $borrado;

    /**
     * @ORM\OneToOne(targetEntity="Responsable", mappedBy="usuario")
     **/ 
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity="Pago", mappedBy="usuario")
     **/
    private $pagos;


    
    /**
    * Para que el usuario sea un usuario válido no debe tener asignado o un responsable o puede tener asigndo uno pero
    * su rol debe ser gestion o consulta, un administrador no puede tener un responsable asignado.
    *
    * @Assert\IsTrue(message = "usuario.invalido") 
    **/    
    public function isUsuarioValido() {
        return (!$this->getResponsable() || ($this->getResponsable() && ($this->getRol() == 'ROLE_GESTION' || $this->getRol() == 'ROLE_CONSULTA') ));
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set habilitado
     *
     * @param boolean $habilitado
     *
     * @return Usuario
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return bool
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set rol
     *
     * @param string $rol
     *
     * @return Usuario
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Usuario
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    public function getBorrado() {
        return $this->borrado;
    }

    public function setBorrado($b) {
        $this->borrado = $b;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set responsable
     *
     * @param \StoreBundle\Entity\Responsable $responsable
     *
     * @return Usuario
     */
    public function setResponsable(\StoreBundle\Entity\Responsable $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \StoreBundle\Entity\Responsable
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     *
     * @return Usuario
     */
    public function addPago(\StoreBundle\Entity\Pago $pago)
    {
        $this->pagos[] = $pago;

        return $this;
    }

    /**
     * Remove pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     */
    public function removePago(\StoreBundle\Entity\Pago $pago)
    {
        $this->pagos->removeElement($pago);
    }

    /**
     * Get pagos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagos()
    {
        return $this->pagos;
    }


    //Metodos de la interfaz AdvancedUserInterface
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->habilitado && !$this->borrado;
    }

    public function getRoles()
    {
       return array($this->getRol());
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        return null;
    }
}
