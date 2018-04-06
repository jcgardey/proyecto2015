<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pago
 *
 * @ORM\Table(name="pago")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\PagoRepository")
 */
class Pago
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPago", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @Assert\NotNull(message="campo.vacio")
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var bool
     *
     * @Assert\NotNull(message="campo.vacio")
     * @ORM\Column(name="becado", type="boolean")
     */
    private $becado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaActualizado", type="datetime")
     */
    private $fechaActualizado;

    /**
     * @ORM\ManyToOne(targetEntity="Alumno", inversedBy="pagos")
     * @ORM\JoinColumn(name="idAlumno", referencedColumnName="idAlumno")
    **/
    private $alumno;

    /**
     * @ORM\ManyToOne(targetEntity="Cuota", inversedBy="pagos")
     * @ORM\JoinColumn(name="idCuota", referencedColumnName="idCuota")
    **/
    private $cuota;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="pagos")
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="idUsuario")
     **/
    private $usuario;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Pago
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set becado
     *
     * @param boolean $becado
     *
     * @return Pago
     */
    public function setBecado($becado)
    {
        $this->becado = $becado;

        return $this;
    }

    /**
     * Get becado
     *
     * @return bool
     */
    public function getBecado()
    {
        return $this->becado;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Pago
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set fechaActualizado
     *
     * @param \DateTime $fechaActualizado
     *
     * @return Pago
     */
    public function setFechaActualizado($fechaActualizado)
    {
        $this->fechaActualizado = $fechaActualizado;

        return $this;
    }

    /**
     * Get fechaActualizado
     *
     * @return \DateTime
     */
    public function getFechaActualizado()
    {
        return $this->fechaActualizado;
    }

    /**
     * Set usuario
     *
     * @param \stdClass $usuario
     *
     * @return Pago
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
     * Set alumno
     *
     * @param \stdClass $alumno
     *
     * @return Pago
     */
    public function setAlumno($alumno)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \stdClass
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set cuota
     *
     * @param \stdClass $cuota
     *
     * @return Pago
     */
    public function setCuota($cuota)
    {
        $this->cuota = $cuota;

        return $this;
    }

    /**
     * Get cuota
     *
     * @return \stdClass
     */
    public function getCuota()
    {
        return $this->cuota;
    }
}
