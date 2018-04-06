<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Responsable
 *
 * @ORM\Table(name="responsable")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\ResponsableRepository")
 */
class Responsable
{
    /**
     * @var int
     *
     * @ORM\Column(name="idResponsable", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"Madre","Padre", "Tutor"}, message= "tipo.invalido")
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fechaNacimiento", type="datetime", length=255)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"Masculino","Femenino"}, message= "sexo.invalido")
     * @ORM\Column(name="sexo", type="string", length=255)
     */
    private $sexo;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Email(message = "email.invalido")
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    
    /**
     * @ORM\Column(name="borrado",type="boolean")
     */
    private $borrado;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
    * @ORM\ManyToMany(targetEntity="Alumno", mappedBy="responsables")
    * 
    **/
    private $alumnos;
    
    /**
    * @Assert\NotNull(message="campo.vacio")
    * @ORM\OneToOne(targetEntity="Usuario", inversedBy="responsable")
    * @ORM\JoinColumn(name="idUsuario", referencedColumnName="idUsuario")
    **/
    private $usuario;


    /**
    * Para que el usuario asignado sea válido, su rol debe ser de gestion o consulta. No puede ser un usuario administrador.
    * A su vez, el usuario no puede estar ya asignado a otro responsable.
    * 
    * @Assert\IsTrue(message="responsable.invalido")
    */   
    public function isUsuarioValido() {
        return $this->getUsuario() && 
        //el usuario que se le asigno al responsable no puede estar asignado a otro responsable, pero puede ocurrir que el 
        //usuario ya este asignado a este responsable
        ( !$this->getUsuario()->getResponsable() || $this->getUsuario()->getResponsable()->getId() == $this->getId() ) 
        && ($this->getUsuario()->getRol() == "ROLE_CONSULTA" || $this->getUsuario()->getRol() == "ROLE_GESTION");
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Responsable
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Responsable
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Responsable
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaNacimiento
     *
     * @param string $fechaNacimiento
     *
     * @return Responsable
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return string
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Responsable
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Responsable
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

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Responsable
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Responsable
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getBorrado() {
        return $this->borrado;
    }

    public function setBorrado($b) {
        $this->borrado = $b;
    }

    /**
     * Set usuario
     *
     * @param \stdClass $usuario
     *
     * @return Responsable
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \stdClass
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->alumnos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add alumno
     *
     * @param \StoreBundle\Entity\Alumno $alumno
     *
     * @return Responsable
     */
    public function addAlumno(\StoreBundle\Entity\Alumno $alumno)
    {
        $this->alumnos[] = $alumno;

        return $this;
    }

    /**
     * Remove alumno
     *
     * @param \StoreBundle\Entity\Alumno $alumno
     */
    public function removeAlumno(\StoreBundle\Entity\Alumno $alumno)
    {
        $this->alumnos->removeElement($alumno);
    }

    /**
     * Get alumnos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlumnos()
    {
        return $this->alumnos;
    }
}
